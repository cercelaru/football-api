<?php
declare(strict_types=1);

namespace FootballApi\Domain\Command;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);

    public function validateCommand(CommandInterface $command): bool;
}