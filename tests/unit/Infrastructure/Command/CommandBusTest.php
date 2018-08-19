<?php

namespace FootballApi\UnitTest\Infrastructure\Command;

use FootballApi\Domain\Command\CommandHandlerInterface;
use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Team\Team;
use FootballApi\Infrastructure\Command\CommandBus;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CommandBusTest extends TestCase
{

    public function testItWillThrowExceptionIfHandlerNotFound()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $bus = new CommandBus(['testcommand' => 'testhandler']);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Cannot find handler for %s command', get_class($command)));
        $bus->handle($command);
    }

    public function testItWillThrowExceptionIfHandlerDoesNotImplementCommandHandlerInterface()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        //bogus handler
        $handler = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $bus = new CommandBus([get_class($command) => $handler]);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf('Command handler %s must implement CommandHandlerInterface', get_class($handler))
        );
        $bus->handle($command);
    }

    public function testItWillHandleCommand()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $handler = $this->getMockBuilder(CommandHandlerInterface::class)->getMock();

        $bus = new CommandBus([get_class($command) => $handler]);

        $handler->expects($this->once())->method('handle')->with($command);

        $bus->handle($command);
    }
}