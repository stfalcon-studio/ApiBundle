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

use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sentry\ClientInterface;
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
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ApiExceptionFormatterListenerTest extends TestCase
{
    private const API_HOST = 'https://test.com';

    private SerializerInterface|MockObject $serializer;
    private ClientInterface|MockObject $sentryClient;
    private TranslatorInterface|MockObject $translator;
    private Request|MockObject $request;
    private JsonResponse|MockObject $response;
    private HttpKernelInterface|MockObject $kernel;
    private ExceptionResponseProcessorInterface|MockObject $exceptionResponseProcessor;
    private ExceptionResponseFactory|MockObject $exceptionResponseFactory;
    private ApiExceptionFormatterListener $exceptionFormatterListener;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->sentryClient = $this->createMock(ClientInterface::class);
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
            ->willReturn('https://not-api-host.com')
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertNull($exceptionEvent->getResponse());
    }

    public function testOnKernelExceptionOnBadRequest(): void
    {
        $httpException = new HttpException(Response::HTTP_BAD_REQUEST, 'bad_request');

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
            ->with('bad_request')
            ->willReturn('Invalid Request.')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('{"error":"invalid_request", "error_description":"test"}')
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"invalid_request", "error_description":"test"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_BAD_REQUEST)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(HttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionOnUnauthorized(): void
    {
        $httpException = new HttpException(Response::HTTP_UNAUTHORIZED, 'no_auth_header');

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
            ->with('no_auth_header')
            ->willReturn('No Auth Header.')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('{"error":"unauthorised_user", "error_description":"test"}')
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"unauthorised_user", "error_description":"test"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_UNAUTHORIZED)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(HttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionOnPaymentRequired(): void
    {
        $httpException = new HttpException(Response::HTTP_PAYMENT_REQUIRED, 'payment_required');

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
            ->with('Error code is not yet specified for this case. Please contact to developer about this case.')
            ->willReturn('Error code is not yet specified for this case. Please contact to developer about this case.')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('{"error":"error_code_is_not_specified", "error_description":"Error code is not yet specified for this case. Please contact to developer about this case."}')
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"error_code_is_not_specified", "error_description":"Error code is not yet specified for this case. Please contact to developer about this case."}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_PAYMENT_REQUIRED)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(HttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionWhenResourceNotFoundCausedByMapEntityAttribute(): void
    {
        $exceptionMessage = '"App\\Entity\\Event\\Event\" object not found by \"Symfony\\Bridge\\Doctrine\\ArgumentResolver\\EntityValueResolver\". The expression \"repository.findClosedEventById(id)\" returned null.';
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

    public function testOnKernelExceptionLockException(): void
    {
        $exceptionMessage = 'optimistic_lock_exception_message';
        $exception = new LockException($exceptionMessage, new \stdClass());

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

        self::assertInstanceOf(LockException::class, $exceptionEvent->getThrowable());
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

    public function testOnKernelExceptionMethodNotAllowedHttpException(): void
    {
        $exceptionMessage = 'method_not_allowed_exception_message';
        $httpException = new MethodNotAllowedHttpException(['POST'], $exceptionMessage);

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

        $message = 'Not allowed HTTP method.';
        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn(sprintf('{"error":"method_not_allowed", "error_description":"%s"}', $message))
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"method_not_allowed", "error_description":"Not allowed HTTP method."}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_METHOD_NOT_ALLOWED)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(MethodNotAllowedHttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionDummyHttpException(): void
    {
        $exceptionMessage = 'exception_message';
        $httpException = new DummyHttpException($exceptionMessage);

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
            ->willReturn('{"error":"method_not_allowed", "error_description":"exception_message"}')
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"method_not_allowed", "error_description":"exception_message"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_FORBIDDEN)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(DummyHttpException::class, $exceptionEvent->getThrowable());
    }

    public function testOnKernelExceptionDomainException(): void
    {
        $httpException = new \DomainException('exception_message', 123);

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
            ->with('internal_server_error_exception_message')
            ->willReturn('internal_server_error_exception_message')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('{"error":"Error code is not yet specified for this case. Please contact to developer about this case.", "error_description":"internal_server_error_exception_message"}')
        ;

        $this->exceptionResponseProcessor
            ->expects(self::never())
            ->method('processResponseForException')
        ;

        $json = '{"error":"Error code is not yet specified for this case. Please contact to developer about this case.", "error_description":"internal_server_error_exception_message"}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_INTERNAL_SERVER_ERROR)
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        self::assertInstanceOf(\DomainException::class, $exceptionEvent->getThrowable());
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
            ->with('internal_server_error_exception_message')
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

    public function testPassesHeadersToResponseFromHttpException(): void
    {
        $httpException = new TooManyRequestsHttpException(58);

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
            ->with('Error code is not yet specified for this case. Please contact to developer about this case.')
            ->willReturn('Error code is not yet specified for this case. Please contact to developer about this case.')
        ;

        $this->serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('{"error":"error_code_is_not_specified", "error_description":"Error code is not yet specified for this case. Please contact to developer about this case."}')
        ;

        $json = '{"error":"error_code_is_not_specified", "error_description":"Error code is not yet specified for this case. Please contact to developer about this case."}';
        $this->exceptionResponseFactory
            ->expects(self::once())
            ->method('createJsonResponse')
            ->with($json, Response::HTTP_TOO_MANY_REQUESTS, ['Retry-After' => 58])
            ->willReturn($this->response)
        ;

        $this->exceptionFormatterListener->__invoke($exceptionEvent);

        $response = $exceptionEvent->getResponse();

        self::assertSame($this->response, $response);
        self::assertInstanceOf(TooManyRequestsHttpException::class, $exceptionEvent->getThrowable());
    }
}
