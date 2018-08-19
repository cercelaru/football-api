<?php

namespace FootballApi\UnitTest\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use FootballApi\Infrastructure\Persistence\Doctrine\Persister;
use PHPUnit\Framework\TestCase;
use stdClass;

class PersisterTest extends TestCase
{

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $this->persister = new Persister($this->entityManager);

        parent::setUp();
    }

    public function testItCanPersistEntity()
    {
        $entity = $this->getMockBuilder(stdClass::class)->getMock();
        $this->entityManager->expects($this->once())->method('persist')->with($entity);
        $this->persister->persist($entity);
    }

    public function testItCanRemoveEntity()
    {
        $entity = $this->getMockBuilder(stdClass::class)->getMock();
        $this->entityManager->expects($this->once())->method('remove')->with($entity);
        $this->persister->remove($entity);
    }

    public function testItCanFlushAllEntities()
    {
        $this->entityManager->expects($this->once())->method('flush');
        $this->persister->flush();
    }
}