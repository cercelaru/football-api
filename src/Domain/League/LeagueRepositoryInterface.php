<?php
declare(strict_types=1);

namespace FootballApi\Domain\League;

use FootballApi\Domain\UuidInterface;

interface LeagueRepositoryInterface
{
    public function findLeagueById(UuidInterface $leagueId): ?League;
}