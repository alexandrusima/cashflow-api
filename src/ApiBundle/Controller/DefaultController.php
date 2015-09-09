<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function getAction($username)
    {
        $user = $this->getDoctrine()->getRepository('ApiBundle:User')->findOneByUsername($username);
        if(!is_object($user)){
          throw $this->createNotFoundException();
        }
        return $user;
    }

    public function getMeAction() {
       $this->forwardIfNotAuthenticated();
       return $this->getAction($this->getUser()->getUsername());
    }

    /**
    * Shortcut to throw a AccessDeniedException($message) if the user is not authenticated
    * @param String $message The message to display (default:'warn.user.notAuthenticated')
    */
    protected function forwardIfNotAuthenticated($message='warn.user.notAuthenticated'){
        if (!is_object($this->getUser())) {
            throw new AccessDeniedException($message);
        }
    }
}
