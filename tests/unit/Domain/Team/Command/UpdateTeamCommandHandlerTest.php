<?php

namespace FootballApi\UnitTest\Domain\Team\Command;

use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Persistence\PersisterInterface;
use FootballApi\Domain\Team\Command\UpdateTeamCommand;
use FootballApi\Domain\Team\Command\UpdateTeamCommandHandler;
use FootballApi\Domain\Team\Team;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use LogicException;

class UpdateTeamCommandHandlerTest extends TestCase
{

    public function setUp()
    {
        $this->persister = $this->getMockBuilder(PersisterInterface::class)->getMock();
        $this->handler = new UpdateTeamCommandHandler($this->persister);

        parent::setUp();
    }

    public function testWillThrowExceptionIfCommandNotValid()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->disableOriginalConstructor()->getMock();
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('Command class not supported : %s', get_class($command)));
        $this->handler->handle($command);
    }

    public function testItCanHandleCommand()
    {
        $command = $this->getMockBuilder(UpdateTeamCommand::class)->disableOriginalConstructor()->getMock();

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $team->expects($this->once())->method('setName')->with('newteamname');
        $team->expects($this->once())->method('setStrip')->with('newteamstrip');


        $command->expects($this->once())->method('getTeam')->willReturn($team);
        $command->expects($this->once())->method('getNewTeamStrip')->willReturn('newteamstrip');
        $command->expects($this->once())->method('getNewTeamName')->willReturn('newteamname');


        $this->persister->expects($this->once())->method('flush');
        $this->handler->handle($command);
    }
}
