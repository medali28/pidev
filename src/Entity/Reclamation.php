<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le nom de medecin est obligatoire")]
    private ?string $medecin = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le sujet est obligatoire")]
    private ?string $sujet = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "la description est obligatoire")]
    private ?string $description_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $avis = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): void
    {
        $this->patient = $patient;
    }

    public function getMedecin(): ?string
    {
        return $this->medecin;
    }

    public function setMedecin(?string $medecin): void
    {
        $this->medecin = $medecin;
    }





    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): static
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getDescriptionRec(): ?string
    {
        return $this->description_rec;
    }

    public function setDescriptionRec(string $description_rec): static
    {
        $this->description_rec = $description_rec;

        return $this;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(string $avis): static
    {
        $this->avis = $avis;

        return $this;
    }


}
