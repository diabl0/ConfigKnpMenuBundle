<?php

namespace CKMB\Bundle\ConfigKnpMenuBundle\DependencyInjection;

use CKMB\Bundle\ConfigKnpMenuBundle\Config\Definition\Builder\MenuTreeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('config_knp_menu', 'array', new MenuTreeBuilder());

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('menu')
            ->useAttributeAsKey('name')

            ->arrayPrototype()
            ->children()
                    ->menuNode('tree')
                    ->menuNodeHierarchy(3)
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
