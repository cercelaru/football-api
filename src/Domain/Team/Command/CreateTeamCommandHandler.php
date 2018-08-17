<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team\Query;

use FootballApi\Domain\Peristence\PersisterInterface;
use FootballApi\Domain\Query\CommandHandlerInterface;
use FootballApi\Domain\Query\CommandInterface;
use FootballApi\Domain\Team\Team;
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

        $team = new Team($command->getTeamName(), $command->getTeamStrip(), $command->getLeague());
        $this->persister->persist($team);
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
