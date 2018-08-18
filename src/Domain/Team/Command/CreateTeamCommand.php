<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Command;

use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\League\League;
use FootballApi\Domain\UuidInterface;

class CreateTeamCommand implements CommandInterface
{

    /** @var UuidInterface $teamId */
    private $teamId;

    /** @var League $league */
    private $league;

    /** @var string $teamName */
    private $teamName;

    /** @var string $teamStrip */
    private $teamStrip;

    public function __construct(UuidInterface $teamId, League $league, string $teamName, string $teamStrip)
    {
        $this->teamId = $teamId;
        $this->league = $league;
        $this->teamName = $teamName;
        $this->teamStrip = $teamStrip;
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

    /**
     * @return UuidInterface
     */
    public function getTeamId(): UuidInterface
    {
        return $this->teamId;
    }

}
