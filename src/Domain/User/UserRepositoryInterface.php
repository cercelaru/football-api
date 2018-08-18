<?php declare(strict_types=1);

namespace FootballApi\Domain\User;

interface UserRepositoryInterface
{

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findUserByUsername(string $username): ?UserInterface;
}