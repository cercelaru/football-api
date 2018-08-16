<?php

namespace FootballApi\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;
use FootballApi\Domain\League\League;
use FootballApi\Domain\Team\TeamRepositoryInterface;

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
}