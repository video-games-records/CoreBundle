<?php

namespace VideoGamesRecords\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('video_games_records_core');

        $rootNode
            ->children()
                ->scalarNode('idSerie')->defaultValue(null)->end()
                ->arrayNode('games')->defaultValue([])->prototype('scalar')->end()
            ->end();

        return $treeBuilder;
    }
}
