<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Command;

use FootballApi\Domain\Command\CommandHandlerInterface;
use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Persistence\ObjectManagerInterface;
use FootballApi\Domain\Team\Team;
use LogicException;

class CreateTeamCommandHandler implements CommandHandlerInterface
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

        $team = new Team(
            $command->getTeamId(),
            $command->getTeamName(),
            $command->getTeamStrip(),
            $command->getLeague()
        );
        $this->objectManager->persist($team);
        $this->objectManager->flush();
    }

    /**
     * @param CommandInterface $command
     *
     * @return bool
     */
    public function validateCommand(CommandInterface $command): bool
    {
        if (!$command instanceof CreateTeamCommand) {
            throw new LogicException(sprintf('Command class not supported : %s', get_class($command)));
        }

        return true;
    }
}
