<?php

namespace FootballApi\Infrastructure\Query;

use FootballApi\Domain\Query\QueryBusInterface;
use FootballApi\Domain\Query\QueryInterface;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQuery;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQueryExecutor;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QueryBus implements QueryBusInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private static $queryToHandlerMap = [
        GetTeamsInLeagueQuery::class => GetTeamsInLeagueQueryExecutor::class
    ];

    public function execute(QueryInterface $query)
    {
        $handlerClass = self::$queryToHandlerMap[get_class($query)];

        $handler = $this->container->get($handlerClass);

        return $handler->execute($query);
    }
}