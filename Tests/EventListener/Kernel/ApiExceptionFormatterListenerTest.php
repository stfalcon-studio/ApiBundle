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

namespace StfalconStudio\ApiBundle\Tests\EventListener\Kernel;

use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sentry\FlushableClientInterface;
use StfalconStudio\ApiBundle\EventListener\Kernel\ApiExceptionFormatterListener;
use StfalconStudio\ApiBundle\Service\Exception\ExceptionResponseFactory;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\ExceptionResponseProcessor;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\ExceptionResponseProcessorInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ApiExceptionFormatterListenerTest extends TestCase
{
    private const API_HOST = 'http://test.com';

    /** @var SerializerInterface|MockObject */
    private $serializer;

    /** @var FlushableClientInterface|MockObject */
    private $sentryClient;

    /** @var TranslatorInterface|MockObject */
    private $translator;

    /** @var Request|MockObject */
    private $request;

    /** @var JsonResponse|MockObject */
    private $response;

    /** @var HttpKernelInterface|MockObject */
    private $kernel;

    /** @var ExceptionResponseProcessorInterface|MockObject */
    private $exceptionResponseProcessor;

    /** @var ExceptionResponseFactory|MockObject */
    private $exceptionResponseFactory;

    private ApiExceptionFormatterListener $exceptionFormatterListener;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->sentryClient = $this->createMock(FlushableClientInterface::class);
        $this->translator = $this->createMock(Translator::class);
        $this->request = $this->createMock(Request::class);
        $this->kernel = $this->createMock(HttpKernelInterface::class);
        $this->response = $this->createMock(JsonResponse::class);
        $this->exceptionResponseProcessor = $this->createMock(ExceptionResponseProcessorInterface::class);
        $this->exceptionResponseFactory = $this->createMock(ExceptionResponseFactory::class);

        $this->exceptionFormatterListener = new ApiExceptionFormatterListener(
            self::API_HOST,
            'prod',
            $this->exceptionResponseProcessor,
            $this->exceptionResponseFactory
        );
        $this->exceptionFormatterListener->setSymfonySerializer($this->serializer);
        $this->exceptionFormatterListener->setSentryClient($this->sentryClient);
        $this->exceptionFormatterListener->setTranslator($this->translator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->serializer,
            $this->sentryClient,
            $this->translator,
            $this->request,
            $this->kernel,
            $this->response,
            $this->exceptionResponseProcessor,
            $this->exceptionResponseFactory,
            $this->exceptionFormatterListener,
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $event = ApiExceptionFormatterListener::getSubscribedEvents();
        self::assertEquals(ExceptionEvent::class, $event->key());
        self::assertEquals('__invoke', $event->current());
        $event->next();
        self::assertFalse($event->valid());
    }

    public function testOnKernelExceptionRequestIsNotMaster(): void
    {
        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::SUB_REQUEST,
            new \Exception()
        );

        $this->request
            ->expects(self::never())
            ->method('getHost')
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertNull($exceptionEvent->getResponse());
    }

    public function testOnKernelExceptionUrlIsNotRelatedToApi(): void
    {
        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            new \Exception()
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn('http://not-api-host.com')
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertNull($exceptionEvent->getResponse());
    }

    public function testOnKernelExceptionWhenHttpException(): void
    {
        $httpException = new HttpException(Response::HTTP_BAD_REQUEST);

        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $httpException
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;


        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('internal_server_error_error_message')
            ->willReturn('test')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('{"error":"internal_server_error", "error_description":"test"}')
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"internal_server_error", "error_description":"test"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_BAD_REQUEST)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(HttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionWhenGenericNotFoundHttpException(): void
    {
        $exceptionMessage = 'Exception test message';
        $httpException = new NotFoundHttpException($exceptionMessage);

        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $httpException
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with($exceptionMessage)
            ->willReturn($exceptionMessage)
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"internal_server_error", "error_description":"%s"}', $exceptionMessage))
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"internal_server_error", "error_description":"Exception test message"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_NOT_FOUND)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(NotFoundHttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionWhenResourceNotFound(): void
    {
        $exceptionMessage = 'App\\Entity\\Allergen\\ServiceCategory object not found by the @ParamConverter annotation.';
        $httpException = new NotFoundHttpException($exceptionMessage);
        $resourceNotFoundMessage = 'Resource not found';

        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $httpException
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('resource_not_found_exception_message')
            ->willReturn($resourceNotFoundMessage)
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"resource_not_found", "error_description":"%s"}', $resourceNotFoundMessage))
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"resource_not_found", "error_description":"Resource not found"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_NOT_FOUND)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(NotFoundHttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionOptimisticLockException(): void
    {
        $exceptionMessage = 'optimistic_lock_exception_message';
        $exception = new OptimisticLockException($exceptionMessage, new \stdClass());

        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $exceptionMessage = 'optimistic_lock_exception_message';
        $exception = new OptimisticLockException($exceptionMessage, new \stdClass());

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with($exceptionMessage)
            ->willReturn($exceptionMessage)
        ;

        $conflictOptimisticLockMessage = 'Someone else has already changed this entity. Please return back, refresh data and apply the changes again!';
        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"conflict_target_resource_update", "error_description":"%s"}', $conflictOptimisticLockMessage))
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"conflict_target_resource_update", "error_description":"Someone else has already changed this entity. Please return back, refresh data and apply the changes again!"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_CONFLICT)
            ->willReturn($this->response)
        ;

        $this->sentryClient
            ->expects(self::once())
            ->method('captureException')
            ->with($exception)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(OptimisticLockException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionAccessDeniedException(): void
    {
        $exceptionMessage = 'access_denied_exception_message';
        $httpException = new AccessDeniedException($exceptionMessage);

        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $httpException
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with($exceptionMessage)
            ->willReturn($exceptionMessage)
        ;

        $message = 'Access to this action is denied for current user.';
        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"access_denied", "error_description":"%s"}', $message))
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"access_denied", "error_description":"Access to this action is denied for current user."}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_FORBIDDEN)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(AccessDeniedException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionWhenUnknownExceptionAndProd(): void
    {
        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            new \Exception()
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('internal_server_error_error_message')
            ->willReturn('Internal Server Error')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('{"error":"internal_server_error","error_description":"Internal Server Error"}')
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"internal_server_error","error_description":"Internal Server Error"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_INTERNAL_SERVER_ERROR)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(\Exception::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionWhenUnknownExceptionAndStag(): void
    {
        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            new \Exception()
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $exceptionMessage = 'Custom Error Message';

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->willReturn($exceptionMessage)
            ->willReturn($exceptionMessage)
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"internal_server_error","error_description":"%s"}', $exceptionMessage))
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"internal_server_error","error_description":"Custom Error Message"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_INTERNAL_SERVER_ERROR)
            ->willReturn($this->response)
        ;

        // Create formatter for stag environment
        $exceptionFormatterSubscriber = new ApiExceptionFormatterListener(self::API_HOST, 'stag', new ExceptionResponseProcessor([]), $this->exceptionResponseFactory);
        $exceptionFormatterSubscriber->setSymfonySerializer($this->serializer);
        $exceptionFormatterSubscriber->setSentryClient($this->sentryClient);
        $exceptionFormatterSubscriber->setTranslator($this->translator);
        $exceptionFormatterSubscriber->__invoke($exceptionEvent);

        self::assertInstanceOf(\Exception::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionWhenCustomAppNotLoggableException(): void
    {
        $exceptionMessage = 'Exception test message';
        $httpException = new DummyCustomAppNotLoggableException($exceptionMessage);

        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $httpException
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with($exceptionMessage)
            ->willReturn($exceptionMessage)
        ;

        $this->sentryClient
            ->expects(self::never())
            ->method('captureException')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"malformed_json","error_description":"%s","new_key":"value"}', $exceptionMessage))
        ;

        $this->exceptionResponseProcessor
            ->expects(self::once())
            ->method('processResponseForException')
            ->with($httpException)
            ->willReturn(['new_key' => 'value'])
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(DummyCustomAppNotLoggableException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionWhenCustomAppLoggableException(): void
    {
        $exceptionMessage = 'Exception test message';
        $httpException = new DummyCustomAppLoggableException($exceptionMessage);

        $exceptionEvent = new ExceptionEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST,
            $httpException
        );

        $this->request
            ->expects(self::once())
            ->method('getHost')
            ->willReturn(self::API_HOST)
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with($exceptionMessage)
            ->willReturn($exceptionMessage)
        ;

        $this->sentryClient
            ->expects(self::once())
            ->method('captureException')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"malformed_json","error_description":"%s"}', $exceptionMessage))
        ;

        $json = '{"error":"malformed_json","error_description":"Exception test message"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_BAD_REQUEST)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);
    }
}