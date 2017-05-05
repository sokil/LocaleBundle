<?php

namespace Sokil\LocaleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('locale');
        $rootNode
            ->children()
                ->scalarNode('query_parameter')->defaultNull()->end()
                ->scalarNode('cookie_parameter')->defaultNull()->end()
                ->booleanNode('path_parameter')->defaultFalse()->end()
                ->arrayNode('locales')
                    ->prototype('scalar')
                ->end()
            ->end();


        return $treeBuilder;
    }
}
