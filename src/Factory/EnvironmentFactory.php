<?php

declare(strict_types=1);

namespace App\Factory;

use App\DBAL\Types\PhpVersionType;
use App\Entity\Environment;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Environment>
 */
final class EnvironmentFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Environment::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'publicId' => substr(md5(self::faker()->randomAscii()), 0, 6),
            'name' => self::faker()->word().' env',
            'phpVersion' => self::faker()->randomElement(PhpVersionType::getChoices())
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
