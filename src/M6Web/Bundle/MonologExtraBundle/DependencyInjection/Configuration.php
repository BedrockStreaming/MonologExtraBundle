<?php

namespace M6Web\Bundle\MonologExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('m6_web_monolog_extra');

        $rootNode
            ->children()
                ->arrayNode('processors')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->defaultValue('contextInformation')->end()
                            ->scalarNode('handler')->end()
                            ->scalarNode('channel')->end()
                            ->variableNode('config')->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function ($v) {
                                return isset($v['handler']) && isset($v['channel']);
                            })
                            ->thenInvalid('You can define a channel or a handler but not both.')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
