<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Command;

use FootballApi\Domain\Command\CommandHandlerInterface;
use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Persistence\PersisterInterface;
use FootballApi\Domain\Team\Team;
use FootballApi\Infrastructure\Uuid;
use LogicException;

class CreateTeamCommandHandler implements CommandHandlerInterface
{

    /** @var PersisterInterface $persister */
    private $persister;

    /**
     * CreateTeamCommandHandler constructor.
     *
     * @param PersisterInterface $persister
     */
    public function __construct(PersisterInterface $persister)
    {
        $this->persister = $persister;
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
        $this->persister->persist($team);
        $this->persister->flush();
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
