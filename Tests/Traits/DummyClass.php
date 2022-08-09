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

namespace StfalconStudio\ApiBundle\Tests\Traits;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Sentry\ClientInterface;
use StfalconStudio\ApiBundle\Request\DtoExtractor;
use StfalconStudio\ApiBundle\Serializer\Serializer;
use StfalconStudio\ApiBundle\Traits;
use StfalconStudio\ApiBundle\Validator\EntityValidator;
use StfalconStudio\ApiBundle\Validator\JsonSchemaValidator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * DummyClass.
 */
final class DummyClass
{
    use Traits\AuthenticationUtilsTrait;
    use Traits\AuthorizationCheckerTrait;
    use Traits\DocumentManagerTrait;
    use Traits\DtoExtractorTrait;
    use Traits\EntityManagerTrait;
    use Traits\EntityValidatorTrait;
    use Traits\EventDispatcherTrait;
    use Traits\FilesystemTrait;
    use Traits\FormFactoryTrait;
    use Traits\HttpClientTrait;
    use Traits\HttpKernelTrait;
    use Traits\JsonSchemaValidatorTrait;
    use Traits\LoggerTrait;
    use Traits\ManagerRegistryTrait;
    use Traits\MessageBusTrait;
    use Traits\RequestStackTrait;
    use Traits\RouterTrait;
    use Traits\SentryClientTrait;
    use Traits\SerializerTrait;
    use Traits\SymfonySerializerTrait;
    use Traits\TokenStorageTrait;
    use Traits\TranslatorTrait;
    use Traits\TwigTrait;
    use Traits\ValidatorTrait;
    use Traits\WorkflowsTrait;

    public function getDtoExtractor(): DtoExtractor
    {
        return $this->dtoExtractor;
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    public function getSentryClient(): ClientInterface
    {
        return $this->sentryClient;
    }

    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    public function getAuthenticationUtils(): AuthenticationUtils
    {
        return $this->authenticationUtils;
    }

    public function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->authorizationChecker;
    }

    public function getBus(): MessageBusInterface
    {
        return $this->bus;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    public function getDocumentManager(): DocumentManager
    {
        return $this->documentManager;
    }

    public function getManagerRegistry(): ManagerRegistry
    {
        return $this->managerRegistry;
    }

    public function getEntityValidator(): EntityValidator
    {
        return $this->entityValidator;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function getHttpKernel(): HttpKernelInterface
    {
        return $this->httpKernel;
    }

    public function getJsonSchemaValidator(): JsonSchemaValidator
    {
        return $this->jsonSchemaValidator;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    public function getTwig(): Environment
    {
        return $this->twig;
    }

    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    public function getWorkflows(): Registry
    {
        return $this->workflows;
    }
}
