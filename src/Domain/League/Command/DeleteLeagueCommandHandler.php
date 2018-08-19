<?php
declare(strict_types=1);

namespace FootballApi\Domain\League\Command;

use FootballApi\Domain\Command\CommandHandlerInterface;
use FootballApi\Domain\Command\CommandInterface;
use FootballApi\Domain\Persistence\PersisterInterface;
use LogicException;

class DeleteLeagueCommandHandler implements CommandHandlerInterface
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

        $this->persister->remove($command->getLeague());
        $this->persister->flush();
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
