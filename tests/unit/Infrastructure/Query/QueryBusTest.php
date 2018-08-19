<?php

namespace FootballApi\UnitTest\Infrastructure\Query;

use FootballApi\Domain\Query\QueryExecutorInterface;
use FootballApi\Domain\Query\QueryInterface;
use FootballApi\Domain\Team\Team;
use FootballApi\Infrastructure\Query\QueryBus;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class QueryBusTest extends TestCase
{

    public function testItWillThrowExceptionIfExecutorNotFound()
    {
        $query = $this->getMockBuilder(QueryInterface::class)->getMock();
        $bus = new QueryBus(['testquery' => 'testexecutor']);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Cannot find executor for %s query', get_class($query)));
        $bus->execute($query);
    }

    public function testItWillThrowExceptionIfExecutorDoesNotImplementQueryExecutorInterface()
    {
        $query = $this->getMockBuilder(QueryInterface::class)->getMock();
        //bogus executor
        $executor = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $bus = new QueryBus([get_class($query) => $executor]);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf('Query executor %s must implement QueryExecutorInterface', get_class($executor))
        );
        $bus->execute($query);
    }

    public function testItWillExecuteQuery()
    {
        $query = $this->getMockBuilder(QueryInterface::class)->getMock();
        $executor = $this->getMockBuilder(QueryExecutorInterface::class)->getMock();

        $bus = new QueryBus([get_class($query) => $executor]);
        $executor->expects($this->once())->method('execute')->with($query);
        $bus->execute($query);
    }
}