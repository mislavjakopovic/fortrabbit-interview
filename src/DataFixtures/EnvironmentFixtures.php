<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Environment;
use App\Story\EnvironmentFixturesStory;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EnvironmentFixtures extends AbstractFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['environment', 'dev', 'test'];
    }

    public function getDependencies(): array
    {
        return [AppFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $this->setup($manager, Environment::class);

        EnvironmentFixturesStory::load();
    }
}
