<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team;

use FootballApi\Domain\League\League;
use FootballApi\Domain\UuidInterface;

interface TeamRepositoryInterface
{
    public function findAllTeamsInLeague(League $league):array;

    public function findTeamById(UuidInterface $uuid):?Team;
}