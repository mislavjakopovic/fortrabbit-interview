<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppRepository::class)]
#[UniqueEntity('publicId')]
#[UniqueEntity('name')]
class App
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

    /** @var Collection<int, Environment> */
    #[ORM\OneToMany(targetEntity: Environment::class, mappedBy: 'app', cascade: ['remove'])]
    private Collection $environments;

    public function __construct()
    {
        $this->environments = new ArrayCollection();
    }

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

    /**
     * @return Environment[]
     */
    public function getEnvironments(): array
    {
        return $this->environments->toArray();
    }

    public function addEnvironment(Environment $environment): self
    {
        if (!$this->environments->contains($environment)) {
            $this->environments[] = $environment;
            $environment->setApp($this);
        }

        return $this;
    }

    public function removeEnvironment(Environment $environment): self
    {
        if ($this->environments->removeElement($environment)) {
            if ($environment->getApp() === $this) {
                $environment->setApp(null);
            }
        }

        return $this;
    }
}
