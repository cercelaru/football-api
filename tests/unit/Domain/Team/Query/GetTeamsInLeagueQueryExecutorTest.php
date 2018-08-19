<?php

namespace FootballApi\UnitTest\Domain\Team\Query;

use FootballApi\Domain\League\League;
use FootballApi\Domain\Query\QueryInterface;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQuery;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQueryExecutor;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use LogicException;

class GetTeamsInLeagueQueryExecutorTest extends TestCase
{

    public function setUp()
    {
        $this->teamRepo = $this->getMockBuilder(TeamRepositoryInterface::class)->getMock();
        $this->executor = new GetTeamsInLeagueQueryExecutor($this->teamRepo);

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
        $query = $this->getMockBuilder(GetTeamsInLeagueQuery::class)->disableOriginalConstructor()->getMock();
        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $query->expects($this->once())->method('getLeague')->willReturn($league);

        $this->teamRepo->expects($this->once())->method('findAllTeamsInLeague')->with($league);

        $this->executor->execute($query);
    }
}
