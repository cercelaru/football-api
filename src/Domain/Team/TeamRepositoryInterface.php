<?php

namespace FootballApi\Domain\Team;

use FootballApi\Domain\League\League;

interface TeamRepositoryInterface
{
    public function findAllTeamsInLeague(League $league):array;
}