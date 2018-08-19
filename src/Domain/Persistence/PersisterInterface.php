<?php
declare(strict_types=1);

namespace FootballApi\Domain\Persistence;

interface PersisterInterface
{
    public function persist(object $entity): void;

    public function remove(object $entity): void;

    public function flush(): void;
}