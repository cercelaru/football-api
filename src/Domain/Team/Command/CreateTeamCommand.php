<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Query;

use FootballApi\Domain\League\League;
use FootballApi\Domain\Query\CommandInterface;

class CreateTeamCommand implements CommandInterface
{

    /** @var League $league */
    private $league;

    /** @var string $teamName */
    private $teamName;

    /** @var string $teamStrip */
    private $teamStrip;

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
     * @return string
     */
    public function getTeamName(): string
    {
        return $this->teamName;
    }

    /**
     * @return string
     */
    public function getTeamStrip(): string
    {
        return $this->teamStrip;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }
}
