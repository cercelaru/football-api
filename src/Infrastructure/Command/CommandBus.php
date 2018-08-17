<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Command;

use FootballApi\Domain\Command\CommandBusInterface;
use FootballApi\Domain\Command\CommandHandlerInterface;
use FootballApi\Domain\Command\CommandInterface;
use RuntimeException;

class CommandBus implements CommandBusInterface
{

    /** @var array $commandToHandlerMap */
    private $commandToHandlerMap;

    /**
     * QueryBus constructor.
     *
     * @param array $commandToHandlerMap
     */
    public function __construct(array $commandToHandlerMap)
    {
        $this->commandToHandlerMap = $commandToHandlerMap;
    }

    public function handle(CommandInterface $command): void
    {
        $commandClass = get_class($command);
        if (!isset($this->commandToHandlerMap[$commandClass])) {
            throw new RuntimeException(sprintf('Cannot find handler for %s command', $commandClass));
        }

        $commandHandler = $this->commandToHandlerMap[$commandClass];
        if (!$commandHandler instanceof CommandHandlerInterface) {
            throw new RuntimeException(
                sprintf('Command handler %s must implement CommandHandlerInterface', get_class($commandHandler))
            );
        }

        $commandHandler->handle($command);
    }
}
