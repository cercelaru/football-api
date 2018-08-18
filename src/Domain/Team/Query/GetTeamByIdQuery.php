<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Query;

use FootballApi\Domain\Query\QueryInterface;
use FootballApi\Domain\UuidInterface;

class GetTeamByIdQuery implements QueryInterface
{
    /**
     * @var UuidInterface
     */
    private $teamId;

    /**
     * GetTeamByIdQuery constructor.
     *
     * @param UuidInterface $teamId
     */
    public function __construct(UuidInterface $teamId)
    {
        $this->teamId = $teamId;
    }

    /**
     * @return UuidInterface
     */
    public function getTeamId(): UuidInterface
    {
        return $this->teamId;
    }

}