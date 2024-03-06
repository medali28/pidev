<?php

namespace App\Entity;

use App\Repository\ProgressBarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProgressBarRepository::class)]
class ProgressBar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\LessThanOrEqual(value: 10000, message: "The target value cannot exceed 10000.")]
    #[Assert\GreaterThan(value: 0, message: "The target value must be greater than zero.")]
    private ?int $target = null;

    #[ORM\Column]
    private ?int $current = null;
    #[ORM\Column]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'pbars')]
    private ?User $user = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarget(): ?int
    {
        return $this->target;
    }

    public function setTarget(int $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function getCurrent(): ?int
    {
        return $this->current;
    }

    public function setCurrent(int $current): static
    {
        $this->current = $current;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
