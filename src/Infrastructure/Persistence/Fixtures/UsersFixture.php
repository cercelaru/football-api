<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure\Persistence\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FootballApi\Domain\User\User;
use FootballApi\Infrastructure\Uuid;

class UsersFixture extends Fixture implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User(new Uuid(), 'testuser');
        $user->setPassword(password_hash('testpass', PASSWORD_BCRYPT));
        $manager->persist($user);

        $manager->flush();
    }
}