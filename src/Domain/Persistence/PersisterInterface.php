<?php
declare(strict_types=1);

namespace FootballApi\Domain\Peristence;

interface PersisterInterface
{
    public function persist(object $entity): void;

    public function flush(): void;
}