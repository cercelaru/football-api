<?php

namespace FootballApi\Infrastructure\Persistence\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FootballApi\Domain\League\League;
use FootballApi\Domain\Team\Team;

class TeamsFixture extends Fixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $league = $this->getReference(sprintf('league%d', $i));

            for ($j = 1; $j <= 5; $j++)
            {
                $teamIndex = 5 * ($i-1) + $j;
                $team = new Team($i, sprintf('Team %d', $teamIndex), sprintf('Strip %d', $teamIndex), $league);
                $manager->persist($team);
            }

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            League::class,
        );
    }
}