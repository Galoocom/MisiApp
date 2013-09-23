<?php
namespace Misi\Bundle\UserBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Doctrine\ORM\NoResultException;
use Sylius\Bundle\CoreBundle\Repository\UserRepository;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use Sylius\Bundle\CoreBundle\Model\User;
use FOS\UserBundle\Propel\User as PropelUser;
use Misi\Bundle\UserBundle\Security\Exception\IncompleteUserException;

class UserProvider extends FOSUBUserProvider implements UserProviderInterface
{
    /**
     * User repository.
     *
     * @var RepositoryInterface
     */
    protected $userRepository;
    
    public function __construct(UserManagerInterface $userManager, array $properties, UserRepository $userRepository) 
    {
        parent::__construct($userManager, $properties);
        
        $this->userRepository = $userRepository;
    }    
    
    public function loadUserByUsername($username)
    {
        $q = $this->userRepository
            ->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin MisiUserBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }
    
    /**
     * {@inheritDoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try {
            return parent::loadUserByOAuthUserResponse($response);
        } catch (UsernameNotFoundException $e) {
            if (null === $user = $this->userManager->findUserByEmail($response->getEmail())) {
                return $this->createUserByOAuthUserResponse($response);
            } else {
                return $this->updateUserByOAuthUserResponse($user, $response);
            }
        }
    }

    /**
     * {@inheritDoc}
    public function connect($user, UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();
        $uniqueId = $response->getUsername();
        $user->addOAuthAccount($providerName, $uniqueId);

        $this->userManager->updateUser($user);
    }
     */

    /**
     * Ad-hoc creation of user
     *
     * @param UserResponseInterface $response
     *
     * @return User
     */
    protected function createUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->userManager->createUser();
        $this->updateUserByOAuthUserResponse($user, $response);

        // set default values taken from OAuth sign-in provider account
        if (null !== $email = $response->getEmail()) {
            $user->setEmail($email);
        }
        if (null === $this->userManager->findUserByUsername($response->getNickname())) {
            $user->setUsername($response->getNickname());
        }
        
        if (!$user->getEmail() || !$user->getUsername()) {
            $e = new AccountNotLinkedException("Please complete you account information");
            //$e->setUser($user);
            throw $e;
        }

        $user->setEnabled(true);

        return $user;
    }

    /**
     * Attach OAuth sign-in provider account to existing user
     *
     * @param User $user
     * @param UserResponseInterface $response
     *
     * @return User
     */
    protected function updateUserByOAuthUserResponse(User $user, UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();
        $providerNameSetter = 'set'.ucfirst($providerName).'Id';
        $user->$providerNameSetter($response->getUsername());

        return $user;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        if (!$user instanceof User && !$user instanceof PropelUser) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\User, but got "%s".', get_class($user)));
        }

        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', $this->userManager->getClass(), get_class($user)));
        }

        if (null === $reloadedUser = $this->userManager->findUserBy(array('id' => $user->getId()))) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }

    
}