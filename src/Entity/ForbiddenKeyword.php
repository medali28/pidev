<?php

namespace App\Entity;

use App\Repository\ForbiddenKeywordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ForbiddenKeywordRepository::class)]
class ForbiddenKeyword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\-_\'\s]+$/',
        message: "The keyword can only contain letters, numbers, hyphens, underscores, and single quotes."
    )]
    private ?string $keyword = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): static
    {
        $this->keyword = $keyword;

        return $this;
    }
}
