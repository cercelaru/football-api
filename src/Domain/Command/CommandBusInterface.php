<?php
declare(strict_types=1);

namespace FootballApi\Domain\Command;

interface CommandBusInterface
{
    public function execute(CommandInterface $query);
}