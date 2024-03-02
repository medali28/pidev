<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\True_;
use PhpParser\Node\Scalar\String_;

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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;
    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;
    #[ORM\Column(length: 50)]
    private ?string $status_rdv = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse_refuse = null;

    #[ORM\ManyToOne(inversedBy: 'id_expert')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $expert = null;

    #[ORM\Column]
    private ?bool $urgence = null;
    #[ORM\Column]
    private ?bool $ReminderEmail = null;


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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): void
    {
        $this->time = $time;
    }



    public function getStatusRdv(): ?string
    {
        return $this->status_rdv;
    }

    public function setStatusRdv(string $status_rdv): static
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
    public function getReminderEmail(): ?bool
    {
        return $this->ReminderEmail;
    }

    public function setReminderEmail(?bool $ReminderEmail): void
    {
        $this->ReminderEmail = $ReminderEmail;
    }

    public function __toString(): string
    {
        $patientName = $this->getPatient() ? $this->getPatient()->getFirstName() : 'Unknown Patient';
        $medecinName = $this->getMedecin() ? $this->getMedecin()->getLastName() : 'Unknown Medecin';
        $medecinLocation = $this->getMedecin() ? $this->getMedecin()->getAddress() : 'Unknown Location';

        return sprintf(
            'RendezVous #%d - Patient: %s, Medecin: %s, Location: %s',
            $this->getId(),
            $patientName,
            $medecinName,
            $medecinLocation
        );
    }




}
