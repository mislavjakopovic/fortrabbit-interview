<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Environment;
use App\Repository\AppRepository;
use App\Repository\EnvironmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\String\ByteString;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class EnvironmentManager
{
    public function __construct(
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private AppRepository $appRepository,
        private EnvironmentRepository $environmentRepository,
    ) {
    }

    public function create(
        string $name,
        string $phpVersion,
        string $appPublicId,
    ): Environment {
        $app = $this->appRepository->findOneBy(['publicId' => $appPublicId]);
        if (empty($app)) {
            throw new BadRequestHttpException('App with public ID ' . $appPublicId . ' not found.');
        }

        $environment = new Environment();
        $environment->setName($name);
        $environment->setPublicId(strtolower(ByteString::fromRandom(6)->toString()));
        $environment->setPhpVersion($phpVersion);
        $environment->setApp($app);

        $errors = $this->validator->validate($environment);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }

        $this->entityManager->persist($environment);
        $this->entityManager->flush();

        return $environment;
    }

    public function delete(Environment $environment): bool
    {
        $id = $environment->getId();

        $this->entityManager->remove($environment);
        $this->entityManager->flush();

        return empty($this->environmentRepository->findOneBy(['id' => $id]));
    }

    public function findByPublicId(string $publicId): ?Environment
    {
        return $this->environmentRepository->findOneBy(['publicId' => $publicId]);
    }

    public function findAll(): array
    {
        return $this->environmentRepository->findAll();
    }
}
