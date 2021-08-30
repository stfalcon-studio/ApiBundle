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

use StfalconStudio\ApiBundle\Security\JwtBlackListService;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\CustomAppExceptionResponseProcessorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class, that loads and manages StfalconApiBundle configuration.
 */
class StfalconApiExtension extends Extension
{
    /**
     * @param mixed[]          $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $jwtBlackListServiceDefinition = $container->getDefinition(JwtBlackListService::class);
        $redisClient = [new Reference($config['redis_client_jwt_black_list'])];

        $jwtBlackListServiceDefinition->addMethodCall('setRedisClientJwtBlackList', $redisClient);

        $container->setParameter('stfalcon_api.api_host', $config['api_host']);
        $container->setParameter('stfalcon_api.json_schema_dir', $config['json_schema_dir']);
        $container->registerForAutoconfiguration(CustomAppExceptionResponseProcessorInterface::class)->addTag('stfalcon_api.exception_response_processor');
    }
}
