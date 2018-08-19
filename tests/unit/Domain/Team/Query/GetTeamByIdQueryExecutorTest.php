<?php

namespace FootballApi\UnitTest\Domain\Team\Query;

use FootballApi\Domain\Query\QueryInterface;
use FootballApi\Domain\Team\Query\GetTeamByIdQuery;
use FootballApi\Domain\Team\Query\GetTeamByIdQueryExecutor;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use FootballApi\Domain\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use LogicException;

class GetTeamByIdQueryExecutorTest extends TestCase
{

    public function setUp()
    {
        $this->teamRepo = $this->getMockBuilder(TeamRepositoryInterface::class)->getMock();
        $this->executor = new GetTeamByIdQueryExecutor($this->teamRepo);

        parent::setUp();
    }

    public function testWillThrowExceptionIfQueryNotValid()
    {
        $query = $this->getMockBuilder(QueryInterface::class)->disableOriginalConstructor()->getMock();
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('Query class not supported : %s', get_class($query)));
        $this->executor->execute($query);
    }

    public function testItCanExecuteQuery()
    {
        $query = $this->getMockBuilder(GetTeamByIdQuery::class)->disableOriginalConstructor()->getMock();
        $teamId = $this->getMockBuilder(UuidInterface::class)->getMock();
        $query->expects($this->once())->method('getTeamId')->willReturn($teamId);

        $this->teamRepo->expects($this->once())->method('findTeamById')->with($teamId);

        $this->executor->execute($query);
    }
}
