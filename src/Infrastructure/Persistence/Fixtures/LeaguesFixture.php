<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FootballApi\Domain\League\League;
use FootballApi\Infrastructure\Uuid;

class LeaguesFixture extends Fixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $id = new Uuid();
            $league = new League($id, sprintf('League %d', $i));
            $this->setReference(sprintf('league%d', $i), $league);
            $manager->persist($league);
        }

        $manager->flush();
    }
}