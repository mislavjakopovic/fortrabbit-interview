<?php

declare(strict_types=1);

namespace App\Model\Request\Environment;

use App\DBAL\Types\PhpVersionType;
use App\Model\Request\RequestInterface;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

class EnvironmentCreateRequest implements RequestInterface
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    #[DoctrineAssert\EnumType(entity: PhpVersionType::class)]
    public string $phpVersion;

    #[Assert\NotBlank]
    public string $appPublicId;
}
