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
}
