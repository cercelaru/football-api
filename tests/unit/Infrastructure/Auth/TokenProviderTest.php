<?php

namespace FootballApi\UnitTest\Infrastructure\Auth;

use Firebase\JWT\JWT;
use FootballApi\Domain\User\UserInterface;
use FootballApi\Domain\UuidInterface;
use FootballApi\Infrastructure\Auth\TokenProvider;
use PHPUnit\Framework\TestCase;
use DateTime;
use DateTimeZone;

class TokenProviderTest extends TestCase
{

    public function testCanProvideToken()
    {
        $user = $this->getMockBuilder(UserInterface::class)->disableOriginalConstructor()->getMock();
        $uuid = $this->getMockBuilder(UuidInterface::class)->getMock();
        $user->expects($this->once())->method('getId')->willReturn($uuid);
        $user->expects($this->once())->method('getUsername')->willReturn('testusername');

        $now = new DateTime('now', new DateTimeZone('UTC'));
        $tenMinutesAgo = clone $now;
        $tenMinutesAgo->modify('-10 minutes');
        $tenMinutesFromNow = clone $now;
        $tenMinutesFromNow->modify('+10 minutes');

        $claims = [
            'iss' => 'Football Api',
            'exp' => $tenMinutesFromNow->getTimestamp(),
            'iat' => $tenMinutesAgo->getTimestamp(),
            'nbf' => $tenMinutesAgo->getTimestamp(),
            'sub' => 'generic',
            'userId' => (string)$uuid,
            'userName' => 'testusername'
        ];

        $expectedToken = JWT::encode($claims, 'somekey', 'HS256');

        $provider = new TokenProvider('somekey');
        $token = $provider->generateToken($user);

        $this->assertEquals($expectedToken, $token);
    }
}