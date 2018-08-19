<?php

namespace FootballApi\UnitTest\Infrastructure\Persistence\Doctrine;

use FootballApi\Domain\League\League;
use FootballApi\Domain\Team\Team;
use FootballApi\Domain\UuidInterface;
use FootballApi\Infrastructure\Persistence\Doctrine\TeamRepository;
use PHPUnit\Framework\TestCase;

class TeamRepositoryTest extends TestCase
{
    public function testItCanFindATeamById()
    {
        $repo = $this->getMockBuilder(TeamRepository::class)
                     ->setMethods(['find'])
                     ->disableOriginalConstructor()
                     ->getMock();

        $leagueId = $this->getMockBuilder(UuidInterface::class)->getMock();

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $repo->expects($this->once())->method('find')->with($leagueId)->willReturn($team);

        $repo->findTeamById($leagueId);
    }

    public function testItCanFindATeamByName()
    {
        $repo = $this->getMockBuilder(TeamRepository::class)
                     ->setMethods(['findOneBy'])
                     ->disableOriginalConstructor()
                     ->getMock();

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $repo->expects($this->once())->method('findOneBy')->with(['name' => 'teamname'])->willReturn($team);

        $repo->findTeamByName('teamname');
    }

    public function testItCanFindAllTeamsInLeague()
    {
        $repo = $this->getMockBuilder(TeamRepository::class)
                     ->setMethods(['findBy'])
                     ->disableOriginalConstructor()
                     ->getMock();

        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $repo->expects($this->once())->method('findBy')->with(['league' => $league])->willReturn([]);

        $repo->findAllTeamsInLeague($league);
    }
}