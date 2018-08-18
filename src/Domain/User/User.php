<?php declare(strict_types=1);

namespace FootballApi\Domain\User;

use FootballApi\Domain\UuidInterface;
use FootballApi\Infrastructure\Uuid;

class User implements UserInterface
{

    /** @var int */
    private $id;

    /** @var string $username */
    private $username;

    /** @var string $password */
    private $password;

    public function __construct(UuidInterface $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    /**
     * @return UuidInterface
     * @throws \Exception
     */
    public function getId(): UuidInterface
    {
        if (is_string($this->id)) {
            return new Uuid($this->id);
        }

        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
         $this->password = $password;

         return $this;
    }

    /**
     * @return array - an array of strings that start with 'ROLE_'
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function isPasswordValid(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
