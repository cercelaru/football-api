<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Query;

use FootballApi\Domain\Command\CommandBusInterface;
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

    public function execute( $command)
    {
        $commandClass = get_class($command);
        if (!isset($this->commandToHandlerMap[$commandClass])) {
            throw new RuntimeException(sprintf('Cannot find handler for %s command', $commandClass));
        }

        $commandHandler = $this->commandToHandlerMap[$commandClass];
        if (!$commandHandler instanceof CommandBusInterface) {
            throw new RuntimeException(
                sprintf('Query executor %s must implement QueryExecutorInterface', get_class($queryExecutor))
            );
        }

        return $queryExecutor->execute($query);
    }
}
