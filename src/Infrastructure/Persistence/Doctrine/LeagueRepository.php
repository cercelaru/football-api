<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use FootballApi\Domain\League\League;
use FootballApi\Domain\League\LeagueRepositoryInterface;
use FootballApi\Domain\UuidInterface;

class LeagueRepository extends EntityRepository implements LeagueRepositoryInterface
{

    /**
     * @param UuidInterface $leagueId
     *
     * @return League|null
     */
    public function findLeagueById(UuidInterface $leagueId): ?League
    {
        return $this->find($leagueId);
    }
}
