<?php

namespace AccessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ApiBundle\Interfaces\UsersHandlerInterface;

class UsersController extends Controller
{
    /**
    * @var UsersHandlerInterface
    */
    private $usersHandler;
    
    public function __construct(UsersHandlerIntrface $usersHandler) {
        $this->usersHandler = $usersHandler;
    }
    
    public function getMeAction()
    {
        return $this->getUser();
    }

    public function getAction()
    {
    }

}
