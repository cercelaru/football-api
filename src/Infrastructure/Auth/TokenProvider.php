<?php

namespace FootballApi\Infrastructure\Auth;

use Firebase\JWT\JWT;
use DateTime;
use DateTimeZone;
use FootballApi\Domain\Auth\TokenProviderInterface;
use FootballApi\Domain\User\UserInterface;

class TokenProvider implements TokenProviderInterface
{

    /**
     * @var string
     */
    private $secretKey;

    /**
     * TokenProvider constructor.
     *
     * @param string $secretKey
     */
    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    public function generateToken(UserInterface $user): string
    {
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
            'userId' => (string)$user->getId(),
            'userName' => $user->getUsername()
        ];

        return JWT::encode($claims, $this->secretKey, 'HS256');
    }
}
