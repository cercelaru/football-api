<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team;

use FootballApi\Domain\League\League;

interface TeamRepositoryInterface
{
    public function findAllTeamsInLeague(League $league):array;
}