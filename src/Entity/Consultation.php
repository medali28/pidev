<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?int $poids = null;

    #[ORM\Column]
    private ?int $taille = null;

    #[ORM\Column]
    private ?int $temperature = null;

    #[ORM\Column]
    private ?int $frequence_cardique = null;

    #[ORM\Column]
    private ?int $respiration = null;

    #[ORM\Column(length: 255)]
    private ?string $conseils = null;

    #[ORM\Column(length: 255)]
    private ?string $medicament = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_prochaine = null;

    #[ORM\OneToOne(inversedBy: 'consultation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
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

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(int $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getFrequenceCardique(): ?int
    {
        return $this->frequence_cardique;
    }

    public function setFrequenceCardique(int $frequence_cardique): static
    {
        $this->frequence_cardique = $frequence_cardique;

        return $this;
    }

    public function getRespiration(): ?int
    {
        return $this->respiration;
    }

    public function setRespiration(int $respiration): static
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


}
