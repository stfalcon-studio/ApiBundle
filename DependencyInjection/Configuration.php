<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('stfalcon_api');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('api_host')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('json_schema_dir')->defaultValue('%kernel.project_dir%/src/Json/Schema/')->cannotBeEmpty()->end()
                ->arrayNode('jwt')
                    ->children()
                        ->booleanNode('enabled')->defaultValue(true)->end()
                        ->scalarNode('redis_client_jwt_black_list')->cannotBeEmpty()->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
