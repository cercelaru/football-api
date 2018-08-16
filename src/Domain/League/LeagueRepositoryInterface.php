<?php

namespace FootballApi\Domain\League;

interface LeagueRepositoryInterface
{
    public function findOneById(int $leagueId):?League;
}