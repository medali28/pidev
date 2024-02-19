<?php

namespace App\Entity;

use App\Repository\CnamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CnamRepository::class)]
class Cnam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Length(min: 8, max: 8)]
    #[Assert\Type(type: 'integer')]
    #[Assert\Length(exactly: 8)]
    private ?string  $Numero_carnet = null;

    #[ORM\Column]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(min: 45, max: 180)]
    private ?int $Prix_consultation = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Consultation $consultation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCarnet(): ?string
    {
        return $this->Numero_carnet;
    }

    public function setNumeroCarnet(string  $Numero_carnet): static
    {
        $this->Numero_carnet = $Numero_carnet;

        return $this;
    }

    public function getPrixConsultation(): ?int
    {
        return $this->Prix_consultation;
    }

    public function setPrixConsultation(int $Prix_consultation): static
    {
        $this->Prix_consultation = $Prix_consultation;

        return $this;
    }

    public function getConsultation(): ?consultation
    {
        return $this->consultation;
    }

    public function setConsultation(consultation $consultation): static
    {
        $this->consultation = $consultation;

        return $this;
    }
}
