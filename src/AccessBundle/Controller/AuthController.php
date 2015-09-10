<?php

namespace AccessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController extends Controller
{
    public function loginAction()
    {

        var_dump($this->get('request')->request->all());
        exit;
    }

    public function registerAction()
    {
    }

    public function forgotAction()
    {
    }

}
