<?php

namespace Misi\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntitiesPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;

class MisiUserBundle extends Bundle
{
    /**
     * Return array with currently supported drivers.
     *
     * @return array
     */
    public static function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM
        );
    }
    
    public function getParent() {
        return 'FOSUserBundle';
    }    
    
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $interfaces = array(
            'Misi\Bundle\UserBundle\Model\FavoriteShopInterface' => 'misi.model.favorite_shop.class',
            'Misi\Bundle\UserBundle\Model\WishProductInterface' => 'misi.model.wish_product.class',
        );

        $container->addCompilerPass(new ResolveDoctrineTargetEntitiesPass('misi_user', $interfaces));

        $mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine/model') => 'Misi\Bundle\UserBundle\Model',
        );

        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, array('doctrine.orm.entity_manager'), 'misi_user.driver.doctrine/orm'));
    }
    
}
