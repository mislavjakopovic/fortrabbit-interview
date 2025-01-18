<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\AppFactory;
use App\Factory\EnvironmentFactory;
use App\Test\AbstractTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppResourceTest extends AbstractTestCase
{
    public function testAppGetCollection(): void
    {
        $client = static::createClient();
        AppFactory::createMany(3);

        $client->request('GET', '/api/app');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = $this->getResponseData();

        $this->assertArrayHasKey('apps', $response);
        $this->assertCount(3, $response['apps']);
    }

    public function testAppGetCollectionEmpty(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/app');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAppGet(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('GET', '/api/app/' . $app->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = $this->getResponseData();

        $this->assertArrayNotHasKey('environments', $response);
    }

    public function testAppGetWithEnvironments(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();
        EnvironmentFactory::createMany(3, ['app' => $app]);

        $client->request('GET', '/api/app/' . $app->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = $this->getResponseData();

        $this->assertArrayHasKey('environments', $response);
        $this->assertCount(3, $response['environments']);
    }

    public function testAppGetEmpty(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/app/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAppPost(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/app', [
            'name' => 'asd123'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = $this->getResponseData();

        $this->assertArrayHasKey('name', $response);
        $this->assertEquals('asd123', $response['name']);
        $this->assertArrayHasKey('publicId', $response);
        $this->assertEquals(6, strlen($response['publicId']));
    }

    public function testAppPostWithTooLongName(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/app', [
            'name' => '1234567890123456789012'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAppPostWithTooShortName(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/app', [
            'name' => 'ab'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAppPostWithSameName(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/app', [
            'name' => 'asd123'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $client->request('POST', '/api/app', [
            'name' => 'asd123'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAppPostWithoutName(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/app');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAppDelete(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('DELETE', '/api/app/' . $app->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testAppDeleteNotFound(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/app/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
