<?php

namespace Misi\Bundle\UserBundle\Repository;

use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Wished products repository.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class WishProductRepository extends EntityRepository
{
    public function createByUserPaginator(UserInterface $user, array $sorting = array())
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
}
