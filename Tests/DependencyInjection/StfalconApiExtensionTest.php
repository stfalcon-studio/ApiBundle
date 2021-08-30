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

namespace StfalconStudio\ApiBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\DependencyInjection\StfalconApiExtension;
use StfalconStudio\ApiBundle\Service\AnnotationProcessor\DtoAnnotationProcessor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * StfalconApiExtensionTest.
 */
final class StfalconApiExtensionTest extends TestCase
{
    private StfalconApiExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new StfalconApiExtension();
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
        $this->container->setParameter('kernel.project_dir', '/tmp');
    }

    protected function tearDown(): void
    {
        unset(
            $this->extension,
            $this->container,
        );
    }

    public function testLoadExtension(): void
    {
        $this->container->loadFromExtension($this->extension->getAlias(), ['api_host' => 'foo', 'redis_client_jwt_black_list' => 'bar']);
        $this->container->compile();

        self::assertSame('/tmp/src/Json/Schema/', $this->container->getParameter('stfalcon_api.json_schema_dir'));
        self::assertSame('foo', $this->container->getParameter('stfalcon_api.api_host'));
        self::assertNotNull($this->container->getParameter('stfalcon_api.redis_client_jwt_black_list'));

        self::assertArrayHasKey(DtoAnnotationProcessor::class, $this->container->getRemovedIds());
        self::assertArrayNotHasKey(DtoAnnotationProcessor::class, $this->container->getDefinitions());

        $childDefinitions = $this->container->getAutoconfiguredInstanceof();
        foreach ($childDefinitions as $childDefinition) {
            self::assertTrue($childDefinition->hasTag('stfalcon_api.exception_response_processor'));
        }
    }

    public function testExceptionOnGettingPrivateService(): void
    {
        $this->container->loadFromExtension($this->extension->getAlias(), ['api_host' => 'foo', 'redis_client_jwt_black_list' => 'bar']);
        $this->container->compile();

        $this->expectException(ServiceNotFoundException::class);
        $this->container->get(DtoAnnotationProcessor::class);
    }
}
