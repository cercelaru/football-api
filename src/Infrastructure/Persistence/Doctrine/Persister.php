<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FootballApi\Domain\Persistence\PersisterInterface;

class Persister implements PersisterInterface
{

    /** @var EntityManager $entityManager */
    private $entityManager;

    /**
     * Persister constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    /**
     * @param object $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(object $entity): void
    {
        $this->entityManager->remove($entity);
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