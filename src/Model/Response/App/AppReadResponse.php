<?php

declare(strict_types=1);

namespace App\Model\Response\App;

use App\Entity\App;
use App\Model\Response\AbstractResponse;
use App\Model\Response\Environment\EnvironmentReadResponse;

class AppReadResponse extends AbstractResponse
{
    public string $name;

    public string $publicId;

    /** @var EnvironmentReadResponse[] */
    public ?array $environments = null;

    public static function fromEntity(object $entity, bool $embed = true): self
    {
        assert($entity instanceof App);
        $response = new self();

        $response->name = $entity->getName();
        $response->publicId = $entity->getPublicId();

        if (true === $embed) {
            foreach ($entity->getEnvironments() as $environment) {
                $response->environments[] = EnvironmentReadResponse::fromEntity($environment, false);
            }
        }

        return $response;
    }
}
