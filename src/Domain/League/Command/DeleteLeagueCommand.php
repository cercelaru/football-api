<?php
declare(strict_types=1);

namespace FootballApi\Domain\League\Command;

use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\League\League;

class DeleteLeagueCommand implements CommandInterface
{

    /** @var League $league */
    private $league;

    /**
     * DeleteLeagueCommand constructor.
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
