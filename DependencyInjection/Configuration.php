<?php

namespace Makoso\DatagridBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('makoso_datagrid');

        $treeBuilder
            ->getRootNode()
                ->children()
                    ->booleanNode('save_filters_in_session')->defaultFalse()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}