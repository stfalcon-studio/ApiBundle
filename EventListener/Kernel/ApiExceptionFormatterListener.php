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

use Doctrine\ORM\OptimisticLockException;
use Sentry\State\Scope;
use StfalconStudio\ApiBundle\Error\BaseErrorNames;
use StfalconStudio\ApiBundle\Exception\CustomAppExceptionInterface;
use StfalconStudio\ApiBundle\Service\Exception\ExceptionResponseFactory;
use StfalconStudio\ApiBundle\Service\Exception\ResponseProcessor\ExceptionResponseProcessorInterface;
use StfalconStudio\ApiBundle\Traits;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
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

    private string $apiHost;
    private string $environment;
    private ExceptionResponseProcessorInterface $exceptionResponseProcessor;
    private ExceptionResponseFactory $exceptionResponseFactory;

    /**
     * @param string                              $apiHost
     * @param string                              $environment
     * @param ExceptionResponseProcessorInterface $exceptionResponseProcessor
     * @param ExceptionResponseFactory            $exceptionResponseFactory
     */
    public function __construct(string $apiHost, string $environment, ExceptionResponseProcessorInterface $exceptionResponseProcessor, ExceptionResponseFactory $exceptionResponseFactory)
    {
        $this->apiHost = $apiHost;
        $this->environment = $environment;
        $this->exceptionResponseProcessor = $exceptionResponseProcessor;
        $this->exceptionResponseFactory = $exceptionResponseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): \Generator
    {
        yield ExceptionEvent::class => '__invoke';
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
                $statusCode = JsonResponse::HTTP_CONFLICT;
                $errorName = BaseErrorNames::CONFLICT_TARGET_RESOURCE_UPDATE;
                $scope = (new Scope())->setExtra('entity', $e->getEntity());
                $this->sentryClient->captureException($e, $scope);
                break;
            case $e instanceof AccessDeniedException:
                $message = 'access_denied_exception_message';
                $statusCode = JsonResponse::HTTP_FORBIDDEN;
                $errorName = BaseErrorNames::ACCESS_DENIED;
                break;
            default:
                if (self::PROD_ENV === $this->environment) {
                    $message = 'internal_server_error';
                } else {
                    $message = $e->getMessage();
                }

                $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
                $errorName = BaseErrorNames::INTERNAL_SERVER_ERROR;
        }

        if ($e instanceof NotFoundHttpException && preg_match('/^(.+) object not found by the @(.+) annotation\.$/', $message)) {
            $errorName = BaseErrorNames::RESOURCE_NOT_FOUND;
            $message = 'resource_not_found_exception_message';
        }

//        ErrorCodes::getErrorNameByErrorCodeAndStatusCode($errorCode, $statusCode);

        $responseData = [
            'error' => $errorName,
            'errorDescription' => $this->translator->trans($message),
        ];

        if ($e instanceof CustomAppExceptionInterface) {
            $exceptionResponse = $this->exceptionResponseProcessor->processResponseForException($e);
            $responseData = array_merge($responseData, $exceptionResponse);
//            $responseData = [...$responseData, ...$exceptionResponse];

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

        $response = $this->exceptionResponseFactory->createJsonResponse($json, $statusCode);

        $event->setResponse($response);
    }

    /**
     * @param int|null $statusCode
     *
     * @return string
     */
    private function getErrorNameByStatusCode(int $statusCode = null): string
    {
        switch ($statusCode) {
            case Response::HTTP_BAD_REQUEST:
                $result = BaseErrorNames::INVALID_REQUEST;
                break;
            case Response::HTTP_FORBIDDEN:
                $result = BaseErrorNames::ACCESS_DENIED;
                break;
            case Response::HTTP_METHOD_NOT_ALLOWED:
                $result = BaseErrorNames::HTTP_METHOD_NOT_ALLOWED;
                break;
            default:
                $result = 'Error code is not yet specified for this case. Please contact to developer about this case.';
        }

        return $result;
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
