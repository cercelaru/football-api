<?php

namespace FootballApi\UnitTest\Domain\League\Command;

use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\League\Command\DeleteLeagueCommand;
use FootballApi\Domain\League\Command\DeleteLeagueCommandHandler;
use FootballApi\Domain\League\League;
use FootballApi\Domain\Persistence\PersisterInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use LogicException;

class DeleteLeagueCommandHandlerTest extends TestCase
{

    public function setUp()
    {
        $this->persister = $this->getMockBuilder(PersisterInterface::class)->getMock();
        $this->handler = new DeleteLeagueCommandHandler($this->persister);

        parent::setUp();
    }

    public function testWillThrowExceptionIfCommandNotValid()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->disableOriginalConstructor()->getMock();
        $this->expectException(LogicException::class);
        $this->handler->handle($command);
    }

    public function testItCanHandleCommand()
    {
        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $command = $this->getMockBuilder(DeleteLeagueCommand::class)->disableOriginalConstructor()->getMock();
        $command->expects($this->once())->method('getLeague')->willReturn($league);

        $this->persister->expects($this->once())->method('remove')->with($league);
        $this->persister->expects($this->once())->method('flush');
        $this->handler->handle($command);
    }
}
