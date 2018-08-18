<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Command;

use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Team\Team;

class UpdateTeamCommand implements CommandInterface
{

    /** @var Team $team */
    private $team;

    /** @var string $newTeamName */
    private $newTeamName;

    /** @var string $newTeamStrip */
    private $newTeamStrip;

    /**
     * UpdateTeamCommand constructor.
     *
     * @param Team $team
     * @param string $newTeamName
     * @param string $newTeamStrip
     */
    public function __construct(Team $team, string $newTeamName, string $newTeamStrip)
    {
        $this->team = $team;
        $this->newTeamName = $newTeamName;
        $this->newTeamStrip = $newTeamStrip;
    }

    /**
     * @return Team
     */
    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * @return string
     */
    public function getNewTeamName(): string
    {
        return $this->newTeamName;
    }

    /**
     * @return string
     */
    public function getNewTeamStrip(): string
    {
        return $this->newTeamStrip;
    }
}

