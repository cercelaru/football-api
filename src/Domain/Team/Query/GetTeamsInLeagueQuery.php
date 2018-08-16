<?php

namespace FootballApi\Domain\Team\Query;

use FootballApi\Domain\League\League;
use FootballApi\Domain\Query\QueryInterface;

class GetTeamsInLeagueQuery implements QueryInterface
{
    /** @var League $league */
    private $league;

    /**
     * GetTeamsInLeagueQuery constructor.
     *
     * @param League $league
     */
    public function __construct(League $league)
    {
        $this->league = $league;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }
}
