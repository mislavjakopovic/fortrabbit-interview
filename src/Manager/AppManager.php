<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\App;
use App\Repository\AppRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\String\ByteString;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class AppManager
{
    public function __construct(
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private AppRepository $appRepository,
    ) {
    }

    public function create(string $name): App
    {
        $app = new App();
        $app->setName($name);
        $app->setPublicId(strtolower(ByteString::fromRandom(6)->toString()));

        $errors = $this->validator->validate($app);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }

        $this->entityManager->persist($app);
        $this->entityManager->flush();

        return $app;
    }

    public function delete(App $app): bool
    {
        $id = $app->getId();

        $this->entityManager->remove($app);
        $this->entityManager->flush();

        return empty($this->appRepository->findOneBy(['id' => $id]));
    }

    public function findByPublicId(string $publicId): ?App
    {
        return $this->appRepository->findOneBy(['publicId' => $publicId]);
    }

    public function findAll(): array
    {
        return $this->appRepository->findAll();
    }
}
