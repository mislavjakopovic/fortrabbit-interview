<?php

declare(strict_types=1);

namespace App\Model\Response\Environment;

use App\Entity\Environment;
use App\Model\Response\AbstractCollectionResponse;

class EnvironmentCollectionResponse extends AbstractCollectionResponse
{
    public array $environments;

    public static function fromEntities(array $entities): self
    {
        $response = new self();

        foreach ($entities as $entity) {
            assert($entity instanceof Environment);
            $response->environments[] = EnvironmentReadResponse::fromEntity($entity);
        }

        return $response;
    }
}
