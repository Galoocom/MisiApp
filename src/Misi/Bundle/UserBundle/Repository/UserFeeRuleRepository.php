<?php

namespace Misi\Bundle\UserBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ProductBundle\Model\ProductInterface;

/**
 * User Fee Rule repository.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class UserFeeRuleRepository extends EntityRepository
{
    public function findFeesByProduct(ProductInterface $product)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $user = $product->getShop()->getUser();
        $taxons = $product->getTaxons();
        
        $queryBuilder
            ->leftJoin($this->getAlias() . '.taxon', 'taxon')
            ->leftJoin($this->getAlias() . '.user', 'user')
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->lte($this->getAlias() . '.dateFrom', ':dateNow'),
                $queryBuilder->expr()->isNull($this->getAlias() . '.dateFrom')
            ))  
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->gte($this->getAlias() . '.dateTo', ':dateNow'),
                $queryBuilder->expr()->isNull($this->getAlias() . '.dateTo')
            ))  
            ->setParameter('dateNow', date("Y-m-d H:i:s"))
        ;
        
        if (!empty($user)) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('user', ':user'),
                    $queryBuilder->expr()->isNull('user')
                ))  
                ->setParameter('user', $user)
            ;
        }
        
        if (!$taxons->isEmpty()) {
            $taxonIds = $taxons->map(function($taxon){
                return $taxon->getId();
            });
            
            $queryBuilder
                ->andWhere($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->in('taxon', $taxonIds->toArray()),
                    $queryBuilder->expr()->isNull('taxon')
                ))  
            ;
        }
        
        $query = $queryBuilder->getQuery();
        
        return $query->getResult();
    }
}
