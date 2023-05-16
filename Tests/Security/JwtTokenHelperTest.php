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

namespace StfalconStudio\ApiBundle\Tests\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Exception\DomainException;
use StfalconStudio\ApiBundle\Security\JwtTokenHelper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class JwtTokenHelperTest extends TestCase
{
    private TokenStorageInterface|MockObject $tokenStorage;
    private JwtTokenHelper $jwtTokenHelper;

    protected function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->jwtTokenHelper = new JwtTokenHelper($this->tokenStorage);
    }

    protected function tearDown(): void
    {
        unset(
            $this->tokenStorage,
            $this->jwtTokenHelper,
        );
    }

    public function testGetJwtUserToken(): void
    {
        $this->tokenStorage
            ->expects(self::once())
            ->method('getToken')
            ->willReturn($this->createStub(JWTUserToken::class))
        ;

        $this->jwtTokenHelper->getJwtUserToken();
    }

    public function testGetJwtUserTokenWithException(): void
    {
        $this->tokenStorage
            ->expects(self::once())
            ->method('getToken')
            ->willReturn(null)
        ;

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Token is not instance of '.JWTUserToken::class);

        $this->jwtTokenHelper->getJwtUserToken();
    }
}
