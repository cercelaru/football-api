<?php
declare(strict_types=1);

namespace FootballApi\Domain\League\Command;

use FootballApi\Domain\Command\CommandHandlerInterface;
use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Persistence\ObjectManagerInterface;
use LogicException;

class DeleteLeagueCommandHandler implements CommandHandlerInterface
{

    /** @var ObjectManagerInterface $objectManager */
    private $objectManager;

    /**
     * CreateTeamCommandHandler constructor.
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param CommandInterface $command
     */
    public function handle(CommandInterface $command)
    {
        $this->validateCommand($command);

        $this->objectManager->remove($command->getLeague());
        $this->objectManager->flush();
    }

    /**
     * @param CommandInterface $command
     *
     * @return bool
     */
    public function validateCommand(CommandInterface $command): bool
    {
        if (!$command instanceof DeleteLeagueCommand) {
            throw new LogicException(sprintf('Command class not supported : %s', get_class($command)));
        }

        return true;
    }
}
