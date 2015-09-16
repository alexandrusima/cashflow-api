<?php 
namespace AccessBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use ApiBundle\Interfaces\UsersHandlerInterface;
use ApiBundle\Interfaces\ApiKeyHandlerInterface;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var UsersHandlerInterface
     */
    private $usersHandler;

    /**
     * @var ApiKeyHandlerInterface
     */
    private $apiKeyHandler;

    public function __construct(UsersHandlerInterface $usersHandler, ApiKeyHandlerInterface $apiKeyHandler) {
        $this->usersHandler = $usersHandler;
        $this->apiKeyHandler = $apiKeyHandler;
    }
    public function getUsernameForApiKey($apiKey)
    {
        return $this->apiKeyHandler->getUsernameFromApiKey($apiKey);
    }

    public function loadUserByUsername($username)
    {
        $user = $this->usersHandler->getByUsername($username);
        if(!$user->isActive()) {
            throw new BadCredentialsException();
        }
    	return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}