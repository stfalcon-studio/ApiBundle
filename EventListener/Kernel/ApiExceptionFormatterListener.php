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

namespace StfalconStudio\ApiBundle\EventListener\Kernel;

use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ORM\OptimisticLockException;
use Sentry\State\Scope;
use StfalconStudio\ApiBundle\Error\BaseErrorNames;
use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;
use StfalconStudio\ApiBundle\Service\Exception\ExceptionResponseFactory;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\ExceptionResponseProcessorInterface;
use StfalconStudio\ApiBundle\Traits;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * ApiExceptionFormatterListener.
 */
final class ApiExceptionFormatterListener implements EventSubscriberInterface
{
    use Traits\SentryClientTrait;
    use Traits\SymfonySerializerTrait;
    use Traits\TranslatorTrait;

    private const PROD_ENV = 'prod';

    /**
     * @param string                              $apiHost
     * @param string                              $environment
     * @param ExceptionResponseProcessorInterface $exceptionResponseProcessor
     * @param ExceptionResponseFactory            $exceptionResponseFactory
     */
    public function __construct(private readonly string $apiHost, private readonly string $environment, private readonly ExceptionResponseProcessorInterface $exceptionResponseProcessor, private readonly ExceptionResponseFactory $exceptionResponseFactory)
    {
    }

    /**
     * @param ExceptionEvent $event
     */
    public function __invoke(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if ($this->urlIsNotRelatedToApi($event->getRequest())) {
            return;
        }

        $e = $event->getThrowable();

        switch (true) {
            case $e instanceof CustomAppExceptionInterface:
                $message = $e->getMessage();
                $statusCode = $e->getStatusCode();
                $errorName = $e->getErrorName();
                break;
            case $e instanceof OptimisticLockException:
                $message = 'optimistic_lock_exception_message';
                $statusCode = Response::HTTP_CONFLICT;
                $errorName = BaseErrorNames::CONFLICT_TARGET_RESOURCE_UPDATE;
                $scope = (new Scope())->setExtra('entity', $e->getEntity());
                $this->sentryClient->captureException($e, $scope);
                break;
            case $e instanceof LockException:
                $message = 'optimistic_lock_exception_message';
                $statusCode = Response::HTTP_CONFLICT;
                $errorName = BaseErrorNames::CONFLICT_TARGET_RESOURCE_UPDATE;
                $scope = (new Scope())->setExtra('document', $e->getDocument());
                $this->sentryClient->captureException($e, $scope);
                break;
            case $e instanceof AccessDeniedException:
                $message = 'access_denied_exception_message';
                $statusCode = Response::HTTP_FORBIDDEN;
                $errorName = BaseErrorNames::ACCESS_DENIED;
                break;
            case $e instanceof MethodNotAllowedHttpException:
                $message = 'method_not_allowed_exception_message';
                $statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
                $errorName = BaseErrorNames::METHOD_NOT_ALLOWED;
                break;
            case $e instanceof NotAcceptableHttpException:
                $message = 'not_acceptable_exception_message';
                $statusCode = Response::HTTP_NOT_ACCEPTABLE;
                $errorName = BaseErrorNames::NOT_ACCEPTABLE;
                break;
            case $e instanceof NotFoundHttpException:
                $message = $e->getMessage();
                $statusCode = $e->getStatusCode();
                $errorName = BaseErrorNames::RESOURCE_NOT_FOUND;

                if (preg_match('/^(.+) object not found by the @(.+) annotation\.$/', $message)
                    || preg_match('/^(.+) object not found by (.+). The expression (.+) returned null\.$/', $message)) {
                    $message = 'resource_not_found_exception_message';
                }
                break;
            default:
                if ($e instanceof HttpExceptionInterface) {
                    $statusCode = $e->getStatusCode();
                } else {
                    $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                }

                $message = $e->getMessage();

                switch ($statusCode) {
                    case Response::HTTP_BAD_REQUEST:
                        $errorName = BaseErrorNames::INVALID_REQUEST;
                        break;
                    case Response::HTTP_UNAUTHORIZED:
                        $errorName = BaseErrorNames::UNAUTHORISED_USER;
                        break;
                    case Response::HTTP_FORBIDDEN:
                        $errorName = BaseErrorNames::ACCESS_DENIED;
                        break;
                    case Response::HTTP_NOT_ACCEPTABLE:
                        $errorName = BaseErrorNames::NOT_ACCEPTABLE;
                        break;
                    case Response::HTTP_INTERNAL_SERVER_ERROR:
                        $errorName = BaseErrorNames::INTERNAL_SERVER_ERROR;

                        if (self::PROD_ENV === $this->environment) {
                            $message = 'internal_server_error_exception_message';
                        }
                        break;
                    default:
                        $errorName = 'error_code_is_not_specified';
                        $message = 'Error code is not yet specified for this case. Please contact to developer about this case.';
                }
        }

        $responseData = [
            'error' => $errorName,
            'errorDescription' => $this->translator->trans($message),
        ];

        if ($e instanceof CustomAppExceptionInterface) {
            $exceptionResponse = $this->exceptionResponseProcessor->processResponseForException($e);
            $responseData = array_merge($responseData, $exceptionResponse);

            if ($e->loggable()) {
                $scope = (new Scope())->setExtra('response_data', $responseData);
                $this->sentryClient->captureException($e, $scope);
            }
        }

        $json = $this->symfonySerializer->serialize(
            $responseData,
            'json',
            ['json_encode_options' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE]
        );

        if ($e instanceof HttpExceptionInterface) {
            $headers = $e->getHeaders();
        } else {
            $headers = [];
        }

        $response = $this->exceptionResponseFactory->createJsonResponse($json, $statusCode, $headers);

        $event->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): iterable
    {
        yield ExceptionEvent::class => '__invoke';
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function urlIsNotRelatedToApi(Request $request): bool
    {
        return $request->getHost() !== $this->apiHost;
    }
}
