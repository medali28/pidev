<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'id_patient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    #[ORM\ManyToOne(inversedBy: 'id_medecin')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $medecin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_heure = null;

    #[ORM\Column]
    private ?int $status_rdv = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse_refuse = null;

    #[ORM\ManyToOne(inversedBy: 'id_expert')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $expert = null;

    #[ORM\Column]
    private ?bool $urgence = null;

    #[ORM\OneToOne(mappedBy: 'rdv', cascade: ['persist', 'remove'])]
    private ?Consultation $consultation = null;

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): void
    {
        $this->consultation = $consultation;
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->date_heure;
    }

    public function setDateHeure(\DateTimeInterface $date_heure): static
    {
        $this->date_heure = $date_heure;

        return $this;
    }

    public function getStatusRdv(): ?int
    {
        return $this->status_rdv;
    }

    public function setStatusRdv(int $status_rdv): static
    {
        $this->status_rdv = $status_rdv;

        return $this;
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

    public function getReponseRefuse(): ?string
    {
        return $this->reponse_refuse;
    }

    public function setReponseRefuse(string $reponse_refuse): static
    {
        $this->reponse_refuse = $reponse_refuse;

        return $this;
    }

    public function getExpert(): ?User
    {
        return $this->expert;
    }

    public function setExpert(?User $expert): static
    {
        $this->expert = $expert;

        return $this;
    }

    public function isUrgence(): ?bool
    {
        return $this->urgence;
    }

    public function setUrgence(bool $urgence): static
    {
        $this->urgence = $urgence;

        return $this;
    }





}
