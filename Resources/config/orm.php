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

use StfalconStudio\ApiBundle\EventListener\ORM\Aggregate\AggregatePartListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('StfalconStudio\ApiBundle\Service\Repository\\', __DIR__.'/../../Service/Repository');
    $services->load('StfalconStudio\ApiBundle\Service\DependentEntity\\', __DIR__.'/../../Service/DependentEntity');
    $services->load('StfalconStudio\ApiBundle\EventListener\ORM\\', __DIR__.'/../../EventListener/ORM');

    $services->set(AggregatePartListener::class, AggregatePartListener::class)->tag('doctrine.event_listener', ['event' => 'onFlush']);
};
