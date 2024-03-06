<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
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
    #[Assert\NotBlank(message: "Le Titre est un champ obligatoire")]
    #[Assert\Regex(pattern: "/^[a-zA-Z]+$/", message: "Le titre '{{ value }}' ne doit contenir que des lettres")]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 400)]
    #[Assert\NotBlank(message: "Le contenu est un champ obligatoire")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9\/\*\x22\x27\(\)&{}\?!:,]+$/", message: "Le contenu '{{ value }}' ne doit contenir que des lettres, des chiffres et les caractères spécifiques autorisés.")]
    private ?string $description = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetempQ ;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $patient = null;

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

    public function getDatetempQ(): ?\DateTimeInterface
    {
        return $this->datetempQ;
    }

    public function setDatetempQ(\DateTimeInterface $datetempQ): static
    {
        $this->datetempQ = $datetempQ;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): void
    {
        $this->patient = $patient;
    }



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
