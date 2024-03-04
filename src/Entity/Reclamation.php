<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;





    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le sujet est obligatoire")]
    private ?string $sujet = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "la description est obligatoire")]
    private ?string $description_rec = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $reponse;


    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE , nullable: true)]
    private ?\DateTimeInterface $date_rec = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $patient = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $medecin = null;





    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->date_rec;
    }

    public function setDateRec(?\DateTimeInterface $date_rec): void
    {
        $this->date_rec = $date_rec;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): void
    {
        $this->etat = $etat;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): void
    {
        $this->reponse = $reponse;
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


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
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

    public function __toString(): string
    {
        return $this->id;
    }


}
