<?php 
namespace AccessBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use ApiBundle\Interfaces\UsersHandlerInterface;

class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var UsersHandlerInterface
     */
    private $usersHandler;

    public function __construct(UsersHandlerInterface $usersHandler) {
        $this->usersHandler = $usersHandler;
    }
    public function getUsernameForApiKey($apiKey)
    {
        return $this->usersHandler->getUsernameFromApiKey($apiKey);
    }

    public function loadUserByUsername($username)
    {
        return $this->usersHandler->getByUsername($username);
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