<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use FootballApi\Domain\League\League;
use FootballApi\Domain\Team\Team;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use FootballApi\Domain\UuidInterface;

class TeamRepository extends EntityRepository implements TeamRepositoryInterface
{
    /**
     * @param League $league
     *
     * @return array
     */
    public function findAllTeamsInLeague(League $league):array
    {
        return $this->findBy(['league' => $league]);
    }

    public function findTeamById(UuidInterface $uuid): ?Team
    {  echo($uuid);die;
        return $this->find($uuid);
    }
}