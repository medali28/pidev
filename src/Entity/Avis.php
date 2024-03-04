<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "la description est obligatoire")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le sujet est obligatoire")]
    private ?string $sujet = null;
    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "le rate obligatoire")]
    #[Assert\Range(
        min: 0,
        max: 10,
        notInRangeMessage: 'You must be between {{ min }} and {{ max }} to enter',
    )]
    public ?int $rate = null;



    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?User $patient = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?User $medecin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reclamation $reclamation = null;







    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): void
    {
        $this->sujet = $sujet;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): void
    {
        $this->rate = $rate;
    }


    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): static
    {
        $this->medecin = $medecin;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): static
    {
        $this->reclamation = $reclamation;

        return $this;
    }






}
