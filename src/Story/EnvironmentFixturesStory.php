<?php

declare(strict_types=1);

namespace App\Story;

use App\DBAL\Types\PhpVersionType;
use App\Factory\AppFactory;
use App\Factory\EnvironmentFactory;
use Faker\Factory;
use Zenstruck\Foundry\Story;

use function Zenstruck\Foundry\Persistence\flush_after;

final class EnvironmentFixturesStory extends Story
{
    public const int NUMBER_OF_FIXTURES = 25;

    private static array $manualFixtures = [
        [
            'name' => 'dev env',
            'publicId' => '654321',
            'phpVersion' => PhpVersionType::PHP_8_1,
            'app' => '123456'
        ],
        [
            'name' => 'stage env',
            'publicId' => 'fedcba',
            'phpVersion' => PhpVersionType::PHP_8_2,
            'app' => 'abcdef'
        ],
        [
            'name' => 'preview env',
            'publicId' => '321cba',
            'phpVersion' => PhpVersionType::PHP_8_3,
            'app' => 'abc123'
        ],
    ];

    public function build(): void
    {
        $faker = Factory::create();

        flush_after(function () use ($faker) {
            $counter = 1;
            foreach (self::$manualFixtures as $manualFixture) {
                $app = $this->getManualFixtureApp($manualFixture);

                EnvironmentFactory::new($manualFixture)
                    ->with(
                        !empty($app) ? ['app' => $app] : []
                    )
                    ->create();
                ++$counter;
            }

            for ($i = $counter; $i - 1 < self::NUMBER_OF_FIXTURES; ++$i) {
                $app = [];
                $app[] = AppFactory::find($faker->numberBetween(1, AppFixturesStory::NUMBER_OF_FIXTURES));
                EnvironmentFactory::new()
                    ->with([
                        'app' => $app[0],
                    ])
                    ->create();
            }
        });
    }

    private function getManualFixtureApp(array $fixture): ?object
    {
        if (empty($fixture['app'])) {
            return null;
        }

        $app = AppFactory::findBy(['publicId' => $fixture['app']]);

        if (!empty($app)) {
            return $app[0];
        }

        return null;
    }
}
