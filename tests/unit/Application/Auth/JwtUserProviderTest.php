<?php

namespace FootballApi\UnitTest\Application\Auth;

use FootballApi\Application\Auth\JwtUserProvider;
use FootballApi\Domain\User\UserInterface;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class JwtUserProviderTest extends TestCase
{

    public function testItCanCreateUserFromValidJwtPayload()
    {
        $userProvider = new JwtUserProvider();
        $user = $userProvider->createFromJwtPayload(
            ['userId' => '40a2694f-6fe8-41ec-b547-37d6bdf6e99a', 'userName' => 'testuser']
        );

        $this->assertInstanceOf(UserInterface::class, $user);
        $this->assertEquals((string)$user->getId(), '40a2694f-6fe8-41ec-b547-37d6bdf6e99a');
        $this->assertEquals((string)$user->getUsername(), 'testuser');
    }

    public function testItWillThrowExceptionIfPayloadIsInvalid()
    {
        $userProvider = new JwtUserProvider();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JWT payload');

        $user = $userProvider->createFromJwtPayload(
            ['userName' => 'testuser']
        );
    }
}