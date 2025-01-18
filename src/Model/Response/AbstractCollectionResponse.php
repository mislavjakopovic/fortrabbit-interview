<?php

declare(strict_types=1);

namespace App\Model\Response;

abstract class AbstractCollectionResponse implements ResponseInterface
{
    abstract public static function fromEntities(array $entities): self;
}
