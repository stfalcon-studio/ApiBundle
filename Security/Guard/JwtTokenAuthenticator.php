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

namespace StfalconStudio\ApiBundle\Security\Guard;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseJWTTokenAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use StfalconStudio\ApiBundle\Exception\DomainException;
use StfalconStudio\ApiBundle\Model\Credentials\CredentialsInterface;
use StfalconStudio\ApiBundle\Security\JwtBlackListService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * JwtTokenAuthenticator.
 */
class JwtTokenAuthenticator extends BaseJWTTokenAuthenticator
{
    private JwtBlackListService $tokenBlackListService;

    /**
     * @param JWTTokenManagerInterface $jwtManager
     * @param EventDispatcherInterface $dispatcher
     * @param TokenExtractorInterface  $tokenExtractor
     * @param JwtBlackListService      $tokenBlackListService
     * @param TokenStorageInterface    $preAuthenticationTokenStorage
     */
    public function __construct(JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher, TokenExtractorInterface $tokenExtractor, JwtBlackListService $tokenBlackListService, TokenStorageInterface $preAuthenticationTokenStorage)
    {
        parent::__construct($jwtManager, $dispatcher, $tokenExtractor, $preAuthenticationTokenStorage);

        $this->tokenBlackListService = $tokenBlackListService;
    }

    /**
     * {@inheritdoc}
     *
     * @throws DomainException
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $result = true;

        if ($user instanceof CredentialsInterface && $user->getCredentialsLastChangedAt() instanceof \DateTime) {
            if ($credentials instanceof PreAuthenticationJWTUserToken && isset($credentials->getPayload()['iat'])) {
                if ((int) $credentials->getPayload()['iat'] < (int) $user->getCredentialsLastChangedAt()->getTimestamp()) {
                    $result = false;
                }
            } else {
                throw new DomainException(sprintf('Expects credentials of type %s with payload option "iat"', PreAuthenticationJWTUserToken::class));
            }
        }

        if ($result && $user instanceof UserInterface) {
            $result = $this->tokenBlackListService->tokenIsNotInBlackList($user, $credentials);
        }

        return $result;
    }
}
