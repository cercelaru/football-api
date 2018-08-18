<?php declare(strict_types=1);

namespace FootballApi\Domain\User;

use FootballApi\Domain\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

interface UserInterface extends SecurityUserInterface
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @param string $password
     *
     * @return bool
     */
    public function isPasswordValid(string $password): bool;

}