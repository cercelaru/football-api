<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use FootballApi\Domain\League\League;
use FootballApi\Domain\League\LeagueRepositoryInterface;

class LeagueRepository extends EntityRepository implements LeagueRepositoryInterface
{
    /**
     * @param int $leagueId
     *
     * @return League|null
     */
    public function findOneById(int $leagueId): ?League
    {
        return $this->find($leagueId);
    }
}
