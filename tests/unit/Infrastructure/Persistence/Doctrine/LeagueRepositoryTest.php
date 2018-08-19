<?php

namespace FootballApi\UnitTest\Infrastructure\Persistence\Doctrine;

use FootballApi\Domain\UuidInterface;
use FootballApi\Infrastructure\Persistence\Doctrine\LeagueRepository;
use PHPUnit\Framework\TestCase;

class LeagueRepositoryTest extends TestCase
{

    public function testItCanFindALeagueByItsId()
    {
        $repo = $this->getMockBuilder(LeagueRepository::class)
                     ->setMethods(['find'])
                     ->disableOriginalConstructor()
                     ->getMock();

        $leagueId = $this->getMockBuilder(UuidInterface::class)->getMock();
        $repo->expects($this->once())->method('find')->with($leagueId);

        $repo->findLeagueById($leagueId);
    }
}