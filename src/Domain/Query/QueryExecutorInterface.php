<?php
declare(strict_types=1);

namespace FootballApi\Domain\Query;

interface QueryExecutorInterface
{
    public function execute(QueryInterface $query);

    public function validateQuery(QueryInterface $query): bool;
}