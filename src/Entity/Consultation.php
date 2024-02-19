<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Float_;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $duree_maladie = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\Column]
    private ?float $taille = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column]
    private ?float $frequence_cardique = null;

    #[ORM\Column]
    private ?float $respiration = null;

    #[ORM\Column(length: 255)]
    private ?string $conseils = null;

    #[ORM\Column(length: 255)]
    private ?string $medicament = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_prochaine = null;

    #[ORM\OneToOne(inversedBy: 'consultation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?RendezVous $rdv = null;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDureeMaladie(): ?\DateTimeInterface
    {
        return $this->duree_maladie;
    }

    public function setDureeMaladie(\DateTimeInterface $duree_maladie): static
    {
        $this->duree_maladie = $duree_maladie;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getFrequenceCardique(): ?float
    {
        return $this->frequence_cardique;
    }

    public function setFrequenceCardique(float $frequence_cardique): static
    {
        $this->frequence_cardique = $frequence_cardique;

        return $this;
    }

    public function getRespiration(): ?float
    {
        return $this->respiration;
    }

    public function setRespiration(float $respiration): static
    {
        $this->respiration = $respiration;

        return $this;
    }

    public function getConseils(): ?string
    {
        return $this->conseils;
    }

    public function setConseils(string $conseils): static
    {
        $this->conseils = $conseils;

        return $this;
    }

    public function getMedicament(): ?string
    {
        return $this->medicament;
    }

    public function setMedicament(string $medicament): static
    {
        $this->medicament = $medicament;

        return $this;
    }

    public function getDateProchaine(): ?\DateTimeInterface
    {
        return $this->date_prochaine;
    }

    public function setDateProchaine(?\DateTimeInterface $date_prochaine): static
    {
        $this->date_prochaine = $date_prochaine;

        return $this;
    }

    public function getRdv(): ?RendezVous
    {
        return $this->rdv;
    }

    public function setRdv(?RendezVous $rdv): void
    {
        $this->rdv = $rdv;
    }
    public function __toString(): string
    {
        // Définir la représentation de la consultation sous forme de chaîne de caractères
        return $this->description . ' - ' . $this->medicament;
    }

}



