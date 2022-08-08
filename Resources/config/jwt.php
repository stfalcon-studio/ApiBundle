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

use Fresh\DateTime\DateTimeHelper;
use JsonSchema\Validator;
use Predis\Client;
use StfalconStudio\ApiBundle\EventListener\ORM\Aggregate\AggregatePartListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->load('StfalconStudio\ApiBundle\EventListener\JWT\\', __DIR__.'/../../EventListener/JWT');
    $services->load('StfalconStudio\ApiBundle\Security\\', __DIR__.'/../../Security');
};
