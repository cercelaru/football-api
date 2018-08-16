<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Query;

use FootballApi\Domain\Query\QueryExecutorInterface;
use FootballApi\Domain\Query\QueryInterface;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use LogicException;

class GetTeamsInLeagueQueryExecutor implements QueryExecutorInterface
{
    private $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function validateQuery(QueryInterface $query): bool
    {
        if (!$query instanceof GetTeamsInLeagueQuery) {
            throw new LogicException(sprintf('Query class not supported : %s', get_class($query)));
        }
    }

    public function execute(QueryInterface $query): array
    {
        return $this->teamRepository->findAllTeamsInLeague($query->getLeague());
    }
}