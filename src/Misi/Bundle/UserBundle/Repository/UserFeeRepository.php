<?php

namespace Misi\Bundle\UserBundle\Repository;

use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * User Fee repository.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class UserFeeRepository extends EntityRepository
{
    public function findByUser(UserInterface $user, array $sorting = array())
    {
        $queryBuilder = $this->getCollectionQueryBuilderByUser($user, $sorting);

        return $this->getPaginator($queryBuilder);
    }
    
    protected function getCollectionQueryBuilderByUser(UserInterface $user, array $sorting = array())
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
            ->innerJoin('o.user', 'user')
            ->andWhere('user = :user')
            ->setParameter('user', $user)
        ;

        $this->applySorting($queryBuilder, $sorting);

        return $queryBuilder;
    }
    
    public function getTotalOwedByUserAndStatus(UserInterface $user, $status)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
            ->select('SUM(' . $this->getAlias() . '.amount)')
            ->innerJoin($this->getAlias() . '.user', 'user')
            ->andWhere($this->getAlias() . '.status = ' . $status)
            ->andWhere('user = :user')
            ->setParameter('user', $user)
        ;
        
        $query = $queryBuilder->getQuery();
        
        $result = $query->getSingleScalarResult();
        
        return is_numeric($result) ? $result : 0;
    }
    
}
