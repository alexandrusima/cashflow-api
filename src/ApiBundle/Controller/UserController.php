<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"list"})
     * @param  interger $id [description]
     * @return [type]           [description]
     */
    public function getAction($id)
    {
        $user = $this->get('users_handler')->findOneById($id);
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
            throw $this->createAccessDeniedException($message);
        }
    }
}
