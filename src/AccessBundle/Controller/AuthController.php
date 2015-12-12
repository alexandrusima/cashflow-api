<?php

namespace AccessBundle\Controller;

use ApiBundle\Entity\User;
use AccessBundle\Entity\ApiKey;

use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

// @TODO: check what to do with api doc
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

    /**
     * @return User
     */
    public function registerAction()
    {
        // @NOTE refactor entity validation
        $reqParametersBag = $this->get('request')->request;

        $username = $reqParametersBag->get('username');
        $password = $reqParametersBag->get('password');
        $firstName = $reqParametersBag->get('first_name');
        $lastName = $reqParametersBag->get('last_name');

        $user = new User();
        $user->setUsername($username);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPassword($password);

        $validator = $this->get('validator');
        $errors = $validator->validate($user, null, array('auth_register'));

        if (count($errors) > 0) {
            return array('validation_error' => $errors);
        }

        // @NOTE refactor password encoding
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);

        // @NOTE refactor getting new salt
        $salt = $user->generateSalt();
        $encodedPassword = $encoder->encodePassword($user->getPassword(), $salt);

        $user->setSalt($salt);
        $user->setPassword($encodedPassword);

        // @NOTE refactor apikeys for newly created user
        $apiKey = new ApiKey();
        $apiKey->setIsActive(true);
        $apiKey->setType('desktop');
        $user->addApiKey($apiKey);


        // we have previously create a mobile apiKey
        // we need to create a desktop api key
        $apiKey = new ApiKey();
        $apiKey->setIsActive(false);
        $apiKey->setType('mobile');
        $user->addApiKey($apiKey);

        
        $apiKey = new ApiKey();
        $apiKey->setIsActive(false);
        $apiKey->setType('tablet');
        $user->addApiKey($apiKey);

        // @NOTE refactor entity saving for new user
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        // @note refactor email building and sending
        $formData = array(
            'email' => $user->getUsername(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'password' => $password
        );

        $template = $this->get('sfk_email_template.loader')
            ->load('AccessBundle:Emails:user_registered.html.twig', $formData);

        // @TODO check the email with images. I think 
        // that the DNS isn't showing any images because it is a local resource.
        $message = \Swift_Message::newInstance()
            ->setSubject($template->getSubject())
            ->setFrom($template->getFrom())
            ->setBody($template->getBody(), 'text/html')
            ->setTo($formData['email']);
        // send email
        $this->get('mailer')->send($message);

        return $user;

    }

    public function forgotAction()
    {
        $email = $this->get('request')->request->get('email');
        /**
         * @var User
         */
        $user = $this->get('users_handler')->getByUsername($email);
        if(!$user) {
            throw $this->createNotFoundException('err.user.notFound');
        }

        if(count($user->getApiKeyByType('password'))) {
            throw $this->createAccessDeniedException('err.token.passwordTokenPresent');
        }

        /**
         * @var ApiKey
         */
        $apiKey = new ApiKey();
        $apiKey->setType('password');
        $apiKey->setIsActive(true);
        $user->addApiKey($apiKey);

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();
        // create password forgot token
            // set token expires to 2 days
        // deactivate the user for login

        $emailData =  array(
            'email' => $user->getUsername(),
            'fullName' => $user->getFullName(),
            'changePasswordLink' => 'https://cashflow.dev/change_password?t=' . $apiKey->getApiKey()
        );

        $template = $this->get('sfk_email_template.loader')
            ->load('AccessBundle:Emails:user_forgot.html.twig', $emailData);

        // @TODO check the email with images. I think 
        // that the DNS isn't showing any images because it is a local resource.
        $message = \Swift_Message::newInstance()
            ->setSubject($template->getSubject())
            ->setFrom($template->getFrom())
            ->setBody($template->getBody(), 'text/html')
            ->setTo($emailData['email']);
        // send email
//        $this->get('mailer')->send($message);
    }

    /**
     * @TODO: move paramters validation
     * outside the actions.
     *
     * Validation of paramters should be done with the
     * default routing.
     *
     */
    public function changePasswordAction()
    {
        $changePasswordToken = $this->get('request')->get('changePasswordToken', false);
        $email = $this->get('request')->get('email', false);
        if(!$email or !$changePasswordToken) {
            throw $this->createNotFoundException('err.param.notFound');
        }

        $user  = $this->get('users_handler')->getByUsername($email);

        if(!$user) {
            throw $this->createNotFoundException('err.user.notFound');
        }

        $oApiHandler = $this->get('apikey_handler');
        if(!$oApiHandler->validate($changePasswordToken)) {
            throw $this->createAccessDeniedException('err.token.invalid');
        }


    }

}
