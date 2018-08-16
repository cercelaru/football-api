<?php

namespace FootballApi\Infrastructure\Persistence\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FootballApi\Domain\League\League;

class LeaguesFixture extends Fixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $league = new League($i, sprintf('League %d', $i));
            $this->setReference(sprintf('league%d', $i), $league);
            $manager->persist($league);
        }

        $manager->flush();
    }
}