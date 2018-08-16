<?php

namespace FootballApi\Domain\Query;

interface QueryBusInterface
{
    public function execute(QueryInterface $query);
}