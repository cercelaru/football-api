<?php declare(strict_types=1);

namespace FootballApi\Application\Auth;

use FootballApi\Domain\User\User;
use FootballApi\Infrastructure\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use InvalidArgumentException;

class JwtUserProvider implements UserProviderInterface
{

    /**
     * @param array $jwtPayload
     *
     * @return SecurityUserInterface
     * @throws \Exception
     */
    public function createFromJwtPayload(array $jwtPayload): UserInterface
    {
        $expectedKeys = [
            'userId' => true,
            'userName' => true
        ];
        if (count(array_diff_key($expectedKeys, $jwtPayload)) > 0) {
            throw new InvalidArgumentException('Invalid JWT payload');
        }

        return new User(
            new Uuid($jwtPayload['userId']), $jwtPayload['userName']
        );
    }

    /**
     * @param string $username
     *
     * @return mixed
     */
    public function loadUserByUsername($username)
    {
        throw new UsernameNotFoundException('Not supported');
    }

    /**
     * @param SecurityUserInterface $user
     *
     * @return SecurityUserInterface|void
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        throw new UnsupportedUserException('Not supported');
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
