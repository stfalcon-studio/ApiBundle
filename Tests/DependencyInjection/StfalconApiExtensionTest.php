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
    /** @var StfalconApiExtension */
    private $extension;

    /** @var ContainerBuilder */
    private $container;

    protected function setUp(): void
    {
        $this->extension = new StfalconApiExtension();
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
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
        $this->container->setParameter('kernel.project_dir', '/tmp');
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        self::assertSame('/tmp/src/Json/Schema/', $this->container->getParameter('stfalcon_api.json_schema_dir'));

        self::assertArrayHasKey(DtoAnnotationProcessor::class, $this->container->getRemovedIds());
        self::assertArrayNotHasKey(DtoAnnotationProcessor::class, $this->container->getDefinitions());

        $this->expectException(ServiceNotFoundException::class);

        $this->container->get(DtoAnnotationProcessor::class);
    }
}
