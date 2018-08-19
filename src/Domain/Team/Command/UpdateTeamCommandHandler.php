<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Command;

use FootballApi\Domain\Command\CommandHandlerInterface;
use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Persistence\PersisterInterface;
use LogicException;

class UpdateTeamCommandHandler implements CommandHandlerInterface
{

    /** @var PersisterInterface $perister */
    private $perister;

    /**
     * CreateTeamCommandHandler constructor.
     *
     * @param PersisterInterface $persister
     */
    public function __construct(PersisterInterface $persister)
    {
        $this->perister = $persister;
    }

    /**
     * @param CommandInterface $command
     */
    public function handle(CommandInterface $command)
    {
        $this->validateCommand($command);

        $team = $command->getTeam();
        $team->setName($command->getNewTeamName());
        $team->setStrip($command->getNewTeamStrip());

        $this->perister->flush();
    }

    /**
     * @param CommandInterface $command
     *
     * @return bool
     */
    public function validateCommand(CommandInterface $command): bool
    {
        if (!$command instanceof UpdateTeamCommand) {
            throw new LogicException(sprintf('Command class not supported : %s', get_class($command)));
        }

        return true;
    }
}
