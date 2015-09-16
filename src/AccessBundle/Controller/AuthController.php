<?php

namespace AccessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use ApiBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @TODO:
 */
class AuthController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"auth_getApiKey"})
     * @return [type] [description]
     */
    public function getApiKeyAction()
    {
        $user = new User();
        $req = $this->get('request');
        
        $username = $req->request->get('username');
        $password = $req->request->get('password');
        
        $user->setUsername($username);
        $user->setPassword($password);


        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return array('validation_error' => $errors);
        }


        $user = $this->get('users_handler')->getByUsername($user->getUsername());

        // check the passowrd.
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $encodedPasword = $encoder->encodePassword($password, $user->getSalt());

        if($encodedPasword != $user->getPassword()) {
            throw new BadCredentialsException("Wrong password in request.");
        }
        else {
           $user->setLastLogin(new \DateTime());
           $em = $this->get('doctrine.orm.entity_manager');
           $em->persist($user);
           $em->flush();
        }

        // @TODO extinde apiKey astfel incat sa fie un api key per device 
        // desktop or mobile ( la mobile pot fi mai multe )
        
        $apiKeys = $user->getApiKeys();

        return $apiKeys->filter(function($entry) {
            return $entry->getType() == 'desktop' and $entry->getIsActive();
        });
        
    }

    public function registerAction()
    {
        var_dump($this->get('request')->request->all());
        exit;
    }

    public function forgotAction()
    {
    }

}
