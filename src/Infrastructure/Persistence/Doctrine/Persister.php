<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use FootballApi\Domain\Persistence\PersisterInterface;

class Persister implements PersisterInterface
{
    /** @var EntityManager $entityManager */
    private $entityManager;

    /**
     * Persister constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flush(): void
    {
        $this->entityManager->flush();
    }
}