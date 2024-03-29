<?php

namespace App\Entity;

use App\Repository\AmbulanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmbulanceRepository::class)]
class Ambulance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $local_actuel_patient = null;

    #[ORM\Column]
    private ?bool $besoin_infirmier = null;

    #[ORM\Column]
    private $latitude;

    #[ORM\Column]
    private $longitude;



    #[ORM\OneToOne(cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?RendezVous $rdv = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocalActuelPatient(): ?string
    {
        return $this->local_actuel_patient;
    }

    public function setLocalActuelPatient(string $local_actuel_patient): static
    {
        $this->local_actuel_patient = $local_actuel_patient;

        return $this;
    }

    public function isBesoinInfirmier(): ?bool
    {
        return $this->besoin_infirmier;
    }

    public function setBesoinInfirmier(bool $besoin_infirmier): static
    {
        $this->besoin_infirmier = $besoin_infirmier;

        return $this;
    }

    public function getRdv(): ?RendezVous
    {
        return $this->rdv;
    }

    public function setRdv(RendezVous $rdv): static
    {
        $this->rdv = $rdv;

        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }


    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }
}
