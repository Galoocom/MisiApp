<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Repository;

use DateTime;
use FOS\UserBundle\Model\UserInterface;
use Sylius\Bundle\SalesBundle\Doctrine\ORM\OrderRepository as BaseOrderRepository;
use YaLinqo\Enumerable;
use Galoo\Bundle\ShopBundle\Model\ShopInterface;

class OrderRepository extends BaseOrderRepository
{
   /**
     * Create shop orders paginator.
     *
     * @param \Galoo\Bundle\ShopBundle\Model\ShopInterface $shop
     * @param array $sorting
     *
     * @return PagerfantaInterface
     */
    public function createByShopPaginator(ShopInterface $shop, array $sorting = array())
    {
        $queryBuilder = $this->getCollectionQueryBuilderByShop($shop, $sorting);

        return $this->getPaginator($queryBuilder);
    }
    
    /**
     * Create user orders paginator.
     *
     * @param UserInterface $user
     * @param array         $sorting
     *
     * @return PagerfantaInterface
     */
    public function createByUserPaginator(UserInterface $user, array $sorting = array())
    {
        $queryBuilder = $this->getCollectionQueryBuilderByUser($user, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Gets orders by user
     *
     * @param  UserInterface $user
     * @param  array         $sorting
     * @return array
     */
    public function findByUser(UserInterface $user, array $sorting = array())
    {
        $queryBuilder = $this->getCollectionQueryBuilderByUser($user, $sorting);

        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return PagerfantaInterface
     */
    public function createFilterPaginator($criteria = array(), $sorting = array())
    {
        $queryBuilder = parent::getCollectionQueryBuilder();

          $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('o.completedAt'));

        if (!empty($criteria['number'])) {
            $queryBuilder
                ->andWhere('o.number LIKE :number')
                ->setParameter('number', '%'.$criteria['number'].'%')
            ;
        }
        if (!empty($criteria['totalFrom'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gte('o.total', ':totalFrom'))
                ->setParameter('totalFrom', $criteria['totalFrom'] * 100)
            ;
        }
        if (!empty($criteria['totalTo'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->lte('o.total', ':totalTo'))
                ->setParameter('totalTo', $criteria['totalTo'] * 100)
            ;
        }
        if (!empty($criteria['createdAtFrom'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gte('o.createdAt', ':createdAtFrom'))
                ->setParameter('createdAtFrom', $criteria['createdAtFrom'])
            ;
        }
        if (!empty($criteria['createdAtTo'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->lte('o.createdAt', ':createdAtTo'))
                ->setParameter('createdAtTo', $criteria['createdAtTo'])
            ;
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = array();
            }
            $sorting['updatedAt'] = 'desc';
        }

        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    public function getTotalStatistics()
    {
        return Enumerable::from($this->findBetweenDates(new DateTime('1 year ago'), new DateTime()))
            ->groupBy(function($order) {
                return $order->getCreatedAt()->format('m');
            }, '$v->getTotal()', function($orders) {
                return Enumerable::from($orders)->sum();
            })
            ->toValues()
            ->toArray()
        ;
    }

    public function getCountStatistics()
    {
        return Enumerable::from($this->findBetweenDates(new DateTime('1 year ago'), new DateTime()))
            ->groupBy(function($order) {
                return $order->getCreatedAt()->format('m');
            }, null, function($orders) {
                return Enumerable::from($orders)->count();
            })
            ->toValues()
            ->toArray()
        ;
    }

    public function findBetweenDates(DateTime $from, DateTime $to)
    {
        $queryBuilder = $this->getCollectionQueryBuilderBetweenDates($from, $to);

        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }

    public function countBetweenDates(DateTime $from, DateTime $to)
    {
        $queryBuilder = $this->getCollectionQueryBuilderBetweenDates($from, $to);

        return $queryBuilder
            ->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function revenueBetweenDates(DateTime $from, DateTime $to)
    {
        $queryBuilder = $this->getCollectionQueryBuilderBetweenDates($from, $to);

        return $queryBuilder
            ->select('sum(o.total)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    protected function getCollectionQueryBuilderBetweenDates(DateTime $from, DateTime $to)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->gte('o.createdAt', ':from'))
            ->andWhere($queryBuilder->expr()->lte('o.createdAt', ':to'))
            ->setParameter('from', $from)
            ->setParameter('to', $to)
        ;
    }

    protected function getCollectionQueryBuilderByShop(ShopInterface $shop, array $sorting = array())
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $queryBuilder
            ->innerJoin('o.shop', 'shop')
            ->andWhere('shop = :shop')
            ->setParameter('shop', $shop)
        ;

        $this->applySorting($queryBuilder, $sorting);

        return $queryBuilder;
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

    protected function getCollectionQueryBuilder()
    {
        $queryBuilder = parent::getCollectionQueryBuilder();

        return $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('o.completedAt'));
    }
}
