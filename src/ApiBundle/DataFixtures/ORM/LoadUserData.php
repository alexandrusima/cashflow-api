<?php

namespace ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ApiBundle\Entity\User;
use AccessBundle\Entity\ApiKey;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
	 /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
	
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setFirstName('Alex');
        $userAdmin->setLastName('Sima');
        $userAdmin->setUsername('admin@localhost.com');
        
        $userAdmin->setSalt(md5(uniqid()));

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userAdmin);
        $userAdmin->setPassword($encoder->encodePassword('test', $userAdmin->getSalt()));
        
        $apiKeyHandler = $this->container->get('apiKey_handler');
        
        $apiKey = new ApiKey();

        // testing reasons
        $key = 'ffe7-ee11-58ef-255e-b337-6ce2-7c24-544b-e315-56a6';

        // $key = sha1( uniqid() . md5( rand() . uniqid() ) );
        // $key = implode('-', str_split($key, 4));
        
        $apiKey->setApiKey($key);
        $apiKey->setIsActive(true);
        $apiKey->setType('desktop');
        
        $userAdmin->addApiKey($apiKey);
        $manager->persist($userAdmin);
        
        $manager->flush();
    }
    
    public function getOrder() {
    	return 1;
    }
}