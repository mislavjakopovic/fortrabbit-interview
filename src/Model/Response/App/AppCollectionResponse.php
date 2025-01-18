<?php

declare(strict_types=1);

namespace App\Model\Response\App;

use App\Entity\App;
use App\Model\Response\AbstractCollectionResponse;

class AppCollectionResponse extends AbstractCollectionResponse
{
    public array $apps;

    public static function fromEntities(array $entities): self
    {
        $response = new self();

        foreach ($entities as $entity) {
            assert($entity instanceof App);
            $response->apps[] = AppReadResponse::fromEntity($entity);
        }

        return $response;
    }
}
