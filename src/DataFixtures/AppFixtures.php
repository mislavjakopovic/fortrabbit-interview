<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\App;
use App\Story\AppFixturesStory;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends AbstractFixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['app', 'dev', 'test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->setup($manager, App::class);

        AppFixturesStory::load();
    }
}
