<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\App;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<App>
 */
final class AppFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return App::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'publicId' => substr(md5(self::faker()->randomAscii()), 0, 6),
            'name' => self::faker()->word() . ' app',
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
