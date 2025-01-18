<?php

declare(strict_types=1);

namespace App\Model\Response\Environment;

use App\Entity\Environment;
use App\Model\Response\AbstractResponse;
use App\Model\Response\App\AppReadResponse;

class EnvironmentReadResponse extends AbstractResponse
{
    public int $id;

    public string $name;

    public string $publicId;

    public ?AppReadResponse $app = null;

    public static function fromEntity(object $entity, bool $embed = true): self
    {
        assert($entity instanceof Environment);
        $response = new self();

        $response->id = $entity->getId();
        $response->name = $entity->getName();
        $response->publicId = $entity->getPublicId();

        if (true === $embed) {
            $response->app = AppReadResponse::fromEntity($entity->getApp(), false);
        }

        return $response;
    }
}
