<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DBAL\Types\PhpVersionType;
use App\Factory\AppFactory;
use App\Factory\EnvironmentFactory;
use App\Test\AbstractTestCase;
use Symfony\Component\HttpFoundation\Response;

class EnvironmentResourceTest extends AbstractTestCase
{
    public function testEnvironmentGetCollection(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();
        EnvironmentFactory::createMany(3, ['app' => $app]);

        $client->request('GET', '/api/environments');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = $this->getResponseData();

        $this->assertArrayHasKey('environments', $response);
        $this->assertCount(3, $response['environments']);
    }

    public function testEnvironmentGetCollectionEmpty(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/environments');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testEnvironmentGet(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();
        $environment = EnvironmentFactory::createOne(['app' => $app]);

        $client->request('GET', '/api/environment/' . $environment->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEnvironmentGetEmpty(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/environment/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testEnvironmentPost(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => 'asd123',
            'phpVersion' => '8.1',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = $this->getResponseData();

        $this->assertArrayHasKey('name', $response);
        $this->assertEquals('asd123', $response['name']);
        $this->assertArrayHasKey('publicId', $response);
        $this->assertEquals(6, strlen($response['publicId']));
        $this->assertArrayHasKey('phpVersion', $response);
        $this->assertTrue(in_array($response['phpVersion'], PhpVersionType::getChoices(), true));
    }

    public function testEnvironmentPostWithTooLongName(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => '1234567890123456789012',
            'phpVersion' => '8.1',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testEnvironmentPostWithTooShortName(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => 'ab',
            'phpVersion' => '8.1',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testEnvironmentPostWithSameName(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => 'asd123',
            'phpVersion' => '8.1',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $client->request('POST', '/api/environment', [
            'name' => 'asd123',
            'phpVersion' => '8.1',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testEnvironmentPostWithoutName(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'phpVersion' => '8.1',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEnvironmentPostWithWrongPhpVersion(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => 'asd123',
            'phpVersion' => '5.6',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEnvironmentPostWithoutPhpVersion(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => 'asd123',
            'appPublicId' => $app->getPublicId()
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEnvironmentPostWithWrongAppPublicId(): void
    {
        $client = static::createClient();
        AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => 'asd123',
            'phpVersion' => '8.1',
            'appPublicId' => '123'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testEnvironmentPostWithoutAppPublicId(): void
    {
        $client = static::createClient();
        AppFactory::createOne();

        $client->request('POST', '/api/environment', [
            'name' => 'asd123',
            'phpVersion' => '8.1',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEnvironmentDelete(): void
    {
        $client = static::createClient();
        $app = AppFactory::createOne();
        $environment = EnvironmentFactory::createOne(['app' => $app]);

        $client->request('DELETE', '/api/environment/' . $environment->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testEnvironmentDeleteNotFound(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/environment/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
