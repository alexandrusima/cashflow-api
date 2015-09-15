<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"list"})
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function getAction($username)
    {
        $user = $this->get('users_handler')->findOneByUsername($username);
        if(!is_object($user)){
          throw $this->createNotFoundException();
        }
        return $user;
    }

    /**
     * @Rest\View(serializerGroups={"me"})
     * @return [type] [description]
     */
    public function getMeAction() {
       $this->forwardIfNotAuthenticated();
       return $this->getUser();
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
