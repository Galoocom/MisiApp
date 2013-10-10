<?php

namespace Misi\Bundle\UserBundle\Repository;

use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Favorite shops repository.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class FavoriteShopRepository extends EntityRepository
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
            #->select('IDENTITY(o.shop)')
            ->innerJoin('o.user', 'user')
            ->innerJoin('o.shop', 'shop')
            ->andWhere('user = :user')
            ->setParameter('user', $user)
        ;

        $this->applySorting($queryBuilder, $sorting);

        return $queryBuilder;
    }
}
