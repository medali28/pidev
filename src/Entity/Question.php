<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 400)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateQ = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tempQ = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetempQ = null;

//    #[ORM\ManyToOne(inversedBy: 'questions')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?Userpast $patient = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Reponse::class, orphanRemoval: true)]
    private Collection $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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

    public function getDateQ(): ?\DateTimeInterface
    {
        return $this->DateQ;
    }

    public function setDateQ(\DateTimeInterface $DateQ): static
    {
        $this->DateQ = $DateQ;

        return $this;
    }

    public function getTempQ(): ?\DateTimeInterface
    {
        return $this->tempQ;
    }

    public function setTempQ(\DateTimeInterface $tempQ): static
    {
        $this->tempQ = $tempQ;

        return $this;
    }

    public function getDatetempQ(): ?\DateTimeInterface
    {
        return $this->datetempQ;
    }

    public function setDatetempQ(\DateTimeInterface $datetempQ): static
    {
        $this->datetempQ = $datetempQ;

        return $this;
    }

//    public function getPatient(): ?Userpast
//    {
//        return $this->patient;
//    }
//
//    public function setPatient(?Userpast $patient): void
//    {
//        $this->patient = $patient;
//    }



    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }
}
