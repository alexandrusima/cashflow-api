<?php

namespace AccessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use ApiBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;

class AuthController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"auth_getApiKey"})
     * @return [type] [description]
     */
    public function getApiKeyAction()
    {
        // @NOTE refactor the entity validation
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

        // @NOTE refactor the password checking
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $encodedPasword = $encoder->encodePassword($password, $user->getSalt());

        if($encodedPasword !== $user->getPassword()) {
            throw new BadCredentialsException("Wrong password in request.");
        }
        else {
           // @NOTE refactor the update of last login
           $user->setLastLogin(new \DateTime());
           $em = $this->get('doctrine.orm.entity_manager');
           $em->persist($user);
           $em->flush();
        }
        
        // @NOTE Refactor geting back the apiKey based on type and device_id
        $apiKeys = $user->getApiKeys();
        
        $mobileDetector = $this->get('mobile_detect.mobile_detector');
        $isDesktop = !$mobileDetector->isMobile() and !$mobileDetector->isTablet();

        // @TODO extinde apiKey astfel incat sa fie un api key per device desktop or mobile or tablet with device_id
        $deviceApiKeys = $apiKeys->filter(function ($entry) use ($isDesktop) {
            return ($isDesktop ? $entry->getType() == 'desktop' : $entry->getType() == 'mobile' ) and $entry->getIsActive();
        });

        return $deviceApiKeys->current();
        
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
