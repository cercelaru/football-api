<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use FootballApi\Domain\User\UserInterface;
use FootballApi\Domain\User\UserRepositoryInterface;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findUserByUsername(string $username): ?UserInterface
    {
        return $this->findOneBy(['username' => $username]);
    }
}
