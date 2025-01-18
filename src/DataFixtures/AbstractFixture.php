<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AbstractFixture extends Fixture
{
    protected static function setup(ObjectManager $manager, string $entityName): void
    {
        assert(empty($manager->getRepository($entityName)->findAll()));

        $classMetadata = $manager->getClassMetadata($entityName);
        $connection = $manager->getConnection();
        $connection->commit();

        $connection->executeStatement('ALTER TABLE ' . $classMetadata->getTableName() . ' AUTO_INCREMENT = 1;');

        $connection->beginTransaction();
    }
}
