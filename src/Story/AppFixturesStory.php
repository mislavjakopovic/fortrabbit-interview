<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\AppFactory;
use Zenstruck\Foundry\Story;

use function Zenstruck\Foundry\Persistence\flush_after;

final class AppFixturesStory extends Story
{
    public const int NUMBER_OF_FIXTURES = 50;

    private static array $manualFixtures = [
        [
            'name' => 'lorem app',
            'publicId' => '123456',
        ],
        [
            'name' => 'ipsum app',
            'publicId' => 'abcdef',
        ],
        [
            'name' => 'lopsum app',
            'publicId' => 'abc123',
        ],
    ];

    public function build(): void
    {
        flush_after(function () {
            AppFactory::createSequence(self::$manualFixtures);
            AppFactory::createMany(self::NUMBER_OF_FIXTURES - count(self::$manualFixtures));
        });
    }
}
