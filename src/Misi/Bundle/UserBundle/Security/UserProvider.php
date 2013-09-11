<?php
namespace Misi\Bundle\UserBundle\Security;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use FOS\UserBundle\Security\UserProvider as FOSUserProvider;
use FOS\UserBundle\Model\UserManagerInterface;
use Doctrine\ORM\NoResultException;
use Sylius\Bundle\CoreBundle\Repository\UserRepository;

class UserProvider extends FOSUserProvider
{
    /**
     * User repository.
     *
     * @var RepositoryInterface
     */
    protected $userRepository;
    
    public function __construct(UserManagerInterface $userManager, UserRepository $userRepository) {
        parent::__construct($userManager);
        
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
}