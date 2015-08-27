<?php

namespace OMA\UsersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OMA\UsersBundle\Entity\User;

class LoadUserData implements FixtureInterface

{
    /**
     * 
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setFirstName('Alexandru');
        $userAdmin->setLastName('sima');
        $userAdmin->setUsername('admin@test.ro');
        $userAdmin->setPassword('test');
        $userAdmin->setLastLogin(new \DateTime());


        $manager->persist($userAdmin);
        $manager->flush();
    }
}