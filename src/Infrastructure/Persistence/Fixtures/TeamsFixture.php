<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FootballApi\Domain\League\League;
use FootballApi\Domain\Team\Team;
use FootballApi\Infrastructure\Uuid;

class TeamsFixture extends Fixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $league = $this->getReference(sprintf('league%d', $i));

            for ($j = 1; $j <= 5; $j++) {
                $teamIndex = 5 * ($i - 1) + $j;
                $id = new Uuid();
                $team = new Team($id, sprintf('Team %d', $teamIndex), sprintf('Strip %d', $teamIndex), $league);
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