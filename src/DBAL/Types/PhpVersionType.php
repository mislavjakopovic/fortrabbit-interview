<?php

declare(strict_types=1);

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class PhpVersionType extends AbstractEnumType
{
    public const string PHP_8_1 = '8.1';
    public const string PHP_8_2 = '8.2';
    public const string PHP_8_3 = '8.3';
    public const string PHP_8_4 = '8.4';

    protected static array $choices = [
        self::PHP_8_1 => '8.1',
        self::PHP_8_2 => '8.2',
        self::PHP_8_3 => '8.3',
        self::PHP_8_4 => '8.4',
    ];
}
