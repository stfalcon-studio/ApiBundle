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

use Doctrine\ORM\EntityManagerInterface;
use Fresh\DateTime\DateTimeHelper;
use JsonSchema\Validator;
use Predis\Client;
use StfalconStudio\ApiBundle\Validator\Constraints\Entity\EntityExistsValidator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->bind('$apiHost', '%stfalcon_api.api_host%')
        ->bind('$jsonSchemaDir', '%stfalcon_api.json_schema_dir%')
        ->bind('$environment', '%env(APP_ENV)%')
        ->bind('iterable $errorResponseProcessors', new TaggedIteratorArgument('stfalcon_api.exception_response_processor'))
        ->bind('$symfonyConstraintViolationListNormalizer', new Reference('serializer.normalizer.constraint_violation_list'))
    ;

    $services->load('StfalconStudio\ApiBundle\\', __DIR__.'/../../{Asset,Request,Serializer,Util,Validator}/');
    $services->load('StfalconStudio\ApiBundle\Service\\', __DIR__.'/../../Service/{AttributeProcessor,DependentEntity,Exception}/');
    $services->load('StfalconStudio\ApiBundle\EventListener\Console\\', __DIR__.'/../../EventListener/Console');
    $services->load('StfalconStudio\ApiBundle\EventListener\Kernel\\', __DIR__.'/../../EventListener/Kernel');
    if (!\interface_exists(EntityManagerInterface::class)) {
        $services->remove(EntityExistsValidator::class);
    }

    $services->set(Client::class, Client::class);
    $services->set(Validator::class, Validator::class);
    $services->set(DateTimeHelper::class, DateTimeHelper::class);
};
