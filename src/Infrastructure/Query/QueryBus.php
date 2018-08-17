<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Query;

use FootballApi\Domain\Query\QueryBusInterface;
use FootballApi\Domain\Query\QueryExecutorInterface;
use FootballApi\Domain\Query\QueryInterface;
use RuntimeException;

class QueryBus implements QueryBusInterface
{

    /** @var array $queryToExecutorMap */
    private $queryToExecutorMap;

    /**
     * QueryBus constructor.
     *
     * @param array $queryToExecutorMap
     */
    public function __construct(array $queryToExecutorMap)
    {
        $this->queryToExecutorMap = $queryToExecutorMap;
    }

    public function execute(QueryInterface $query)
    {
        $queryClass = get_class($query);
        if (!isset($this->queryToExecutorMap[$queryClass])) {
            throw new RuntimeException(sprintf('Cannot find executor for %s query', $queryClass));
        }

        $queryExecutor = $this->queryToExecutorMap[$queryClass];
        if (!$queryExecutor instanceof QueryExecutorInterface) {
            throw new RuntimeException(
                sprintf('Query executor %s must implement QueryExecutorInterface', get_class($queryExecutor))
            );
        }

        return $queryExecutor->execute($query);
    }
}
