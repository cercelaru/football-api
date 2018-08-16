<?php
declare(strict_types=1);

namespace FootballApi\Domain\League;

interface LeagueRepositoryInterface
{
    public function findOneById(int $leagueId):?League;
}