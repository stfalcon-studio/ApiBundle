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

use JsonSchema\Validator;
use Predis\Client;
use StfalconStudio\ApiBundle\EventListener\ORM\Aggregate\AggregatePartListener;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void
{
    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->bind('$apiHost', '%stfalcon_api.api_host%')
        ->bind('$jsonSchemaDir', '%stfalcon_api.json_schema_dir%')
        ->bind('$environment', '%env(APP_ENV)%')
        ->bind('iterable $errorResponseProcessors', new TaggedIteratorArgument('stfalcon_api.exception_response_processor'))
    ;

    $services->load('StfalconStudio\ApiBundle\\', __DIR__.'/../../{Asset,EventListener,Request,Security,Serializer,Service,Util,Validator}/');
    $services->set(AggregatePartListener::class, AggregatePartListener::class)->tag('doctrine.event_listener', ['event' => 'onFlush']);
    $services->set(Client::class, Client::class);
    $services->set(Validator::class, Validator::class);
};
