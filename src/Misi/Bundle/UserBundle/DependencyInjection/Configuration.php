<?php

namespace Misi\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * UserBundle configuration
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('misi_user');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->cannotBeOverwritten()->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('engine')->defaultValue('twig')->cannotBeEmpty()->end()
            ->end()
        ;
        
        $this->addClassesSection($rootNode);
        $this->addProductFeesSection($rootNode);

        return $treeBuilder;
    }
    
    
    /**
     * Adds `classes` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addClassesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('classes')
                    ->isRequired()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('product_fee')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('repository')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
    
    /**
     * Adds `product_fee` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addProductFeesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('product_fee')
                    ->isRequired()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->floatNode('amount')->defaultValue(0)->end()
                        ->scalarNode('currency')->defaultValue('EUR')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }    
    
}
