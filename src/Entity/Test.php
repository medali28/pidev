<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $posted_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostedAt(): ?\DateTimeImmutable
    {
        return $this->posted_at;
    }

    public function setPostedAt(\DateTimeImmutable $posted_at): static
    {
        $this->posted_at = $posted_at;

        return $this;
    }
}
