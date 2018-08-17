<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Query;

use FootballApi\Domain\Query\QueryExecutorInterface;
use FootballApi\Domain\Query\QueryInterface;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use LogicException;

class GetTeamsInLeagueQueryExecutor implements QueryExecutorInterface
{
    /** @var TeamRepositoryInterface $teamRepository */
    private $teamRepository;

    /**
     * GetTeamsInLeagueQueryExecutor constructor.
     *
     * @param TeamRepositoryInterface $teamRepository
     */
    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param QueryInterface $query
     *
     * @return bool
     */
    public function validateQuery(QueryInterface $query): bool
    {
        if (!$query instanceof GetTeamsInLeagueQuery) {
            throw new LogicException(sprintf('Query class not supported : %s', get_class($query)));
        }

        return true;
    }

    /**
     * @param QueryInterface $query
     *
     * @return array
     */
    public function execute(QueryInterface $query): array
    {
        $this->validateQuery($query);

        return $this->teamRepository->findAllTeamsInLeague($query->getLeague());
    }
}