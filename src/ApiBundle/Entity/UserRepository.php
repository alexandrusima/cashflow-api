<?php

namespace ApiBundle\Entity;

use ApiBundle\Interfaces\UsersHandlerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository implements UsersHandlerInterface
{
    public function getByUsername($username) {
    	return $this->findOneBy(array('username' => $username));
    }
}
