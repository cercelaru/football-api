<?php

namespace FootballApi\UnitTest\Domain\Team\Command;

use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\League\League;
use FootballApi\Domain\Persistence\PersisterInterface;
use FootballApi\Domain\Team\Command\CreateTeamCommand;
use FootballApi\Domain\Team\Command\CreateTeamCommandHandler;
use FootballApi\Domain\Team\Team;
use FootballApi\Domain\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use LogicException;

class CreateTeamCommandHandlerTest extends TestCase
{

    public function setUp()
    {
        $this->persister = $this->getMockBuilder(PersisterInterface::class)->getMock();
        $this->handler = new CreateTeamCommandHandler($this->persister);

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
        $command = $this->getMockBuilder(CreateTeamCommand::class)->disableOriginalConstructor()->getMock();

        $teamId = $this->getMockBuilder(UuidInterface::class)->getMock();
        $teamId->method('__toString')->willReturn(1234);

        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $uuid = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuid->method('__toString')->willReturn(7890);
        $league->method('getId')->willReturn($uuid);

        $command->expects($this->once())->method('getTeamId')->willReturn($teamId);
        $command->expects($this->once())->method('getTeamName')->willReturn('teamname');
        $command->expects($this->once())->method('getTeamStrip')->willReturn('teamstrip');
        $command->expects($this->once())->method('getLeague')->willReturn($league);

        $this->persister->expects($this->once())->method('persist')->with(
            $this->callback(
                function (Team $team) {
                    return $team->getName() == 'teamname'
                        && $team->getStrip() == 'teamstrip'
                        && (string)$team->getId() == 1234
                        && (string)$team->getLeague()->getId() == 7890;
                }
            )
        );

        $this->persister->expects($this->once())->method('flush');
        $this->handler->handle($command);
    }
}
