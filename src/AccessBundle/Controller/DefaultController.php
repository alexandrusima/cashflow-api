<?php

namespace AccessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AccessBundle:Default:index.html.twig', array('name' => $name));
    }
}
