<?php

declare(strict_types=1);

namespace App\Model\Response;

abstract class AbstractResponse implements ResponseInterface
{
    abstract public static function fromEntity(object $entity, bool $embed = true): self;
}
