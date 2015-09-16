<?php 
namespace ApiBundle\Interfaces;

interface UsersHandlerInterface {
    public function getByUsername($username);
}