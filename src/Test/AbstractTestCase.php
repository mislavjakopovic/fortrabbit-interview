<?php

declare(strict_types=1);

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractTestCase extends WebTestCase
{
    protected function getResponseData(): ?array
    {
        $response = self::getClient()->getResponse();

        return json_decode($response->getContent(), true);
    }
}
