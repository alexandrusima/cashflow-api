<?php

namespace ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ApiBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setFirstName('admin');
        $userAdmin->setLastName('admin');
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('test');

        $manager->persist($userAdmin);
        $manager->flush();
    }
}