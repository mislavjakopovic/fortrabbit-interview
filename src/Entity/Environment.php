<?php

declare(strict_types=1);

namespace App\Entity;

use App\DBAL\Types\PhpVersionType;
use App\Repository\EnvironmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnvironmentRepository::class)]
#[UniqueEntity('publicId')]
#[UniqueEntity('name')]
class Environment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[Assert\Length(exactly: 6)]
    #[ORM\Column(type: Types::STRING, length: 6)]
    private ?string $publicId = null;

    #[Assert\Length(min: 3, max: 20)]
    #[ORM\Column(type: Types::STRING, length: 20)]
    private ?string $name = null;

    #[ORM\Column(type: 'PhpVersionType')]
    #[DoctrineAssert\EnumType(entity: PhpVersionType::class)]
    private ?string $phpVersion = PhpVersionType::PHP_8_1;

    #[ORM\ManyToOne(targetEntity: App::class, inversedBy: 'environments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?App $app = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPublicId(): ?string
    {
        return $this->publicId;
    }

    public function setPublicId(?string $publicId): void
    {
        $this->publicId = $publicId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPhpVersion(): ?string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(?string $phpVersion): void
    {
        $this->phpVersion = $phpVersion;
    }

    public function getApp(): ?App
    {
        return $this->app;
    }

    public function setApp(?App $app): void
    {
        $this->app = $app;
    }
}
