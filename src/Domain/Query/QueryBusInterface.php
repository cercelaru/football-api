<?php
declare(strict_types=1);

namespace FootballApi\Domain\Query;

interface QueryBusInterface
{
    public function execute(QueryInterface $query);
}