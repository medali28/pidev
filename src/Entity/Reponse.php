<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 400)]
    private ?string $description_r = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetemp_r = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'reponses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $medecin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pinned = null;

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): void
    {
        $this->medecin = $medecin;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionR(): ?string
    {
        return $this->description_r;
    }

    public function setDescriptionR(string $description_r): static
    {
        $this->description_r = $description_r;

        return $this;
    }

    public function getDatetempR(): ?\DateTimeInterface
    {
        return $this->datetemp_r;
    }

    public function setDatetempR(\DateTimeInterface $datetemp_r): static
    {
        $this->datetemp_r = $datetemp_r;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): void
    {
        $this->question = $question;
    }

    public function isPinned(): ?bool
    {
        return $this->pinned;
    }

    public function setPinned(?bool $pinned): static
    {
        $this->pinned = $pinned;

        return $this;
    }




}
