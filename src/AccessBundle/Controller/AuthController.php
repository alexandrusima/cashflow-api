<?php

namespace AccessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController extends Controller
{
    public function loginAction($username, $password)
    {
        var_dump($username, $password);
        exit;
    }

    public function registerAction()
    {
    }

    public function forgotAction()
    {
    }

}
