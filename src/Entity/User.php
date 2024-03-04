<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: "email invalid format")]
    #[Assert\NotBlank(message: " email must be non-empty")]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "password must be non-empty")]
    private ?string $password = null;
    #[Assert\EqualTo(propertyPath: "password", message: "You did not type the same password")]
    public $confirm_password;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "First name must be non-empty")]
    private ?string $first_name = null;

    #[ORM\Column(length: 255,)]
    #[Assert\NotBlank(message: "Last name must be non-empty")]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Tel must be non-empty")]
    #[Assert\Length(min: 8, minMessage: "minimum 8 chiffres")]
    private ?int $num_tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_create_compte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_modify_password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_modify_data = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_naissance = null;

    #[Assert\File(maxSize: "1M", mimeTypes: ["image/jpeg", "image/png"], mimeTypesMessage: "Veuillez télécharger une image au format JPG ou PNG")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $maladie_chronique = null;

    #[ORM\Column(nullable: true)]
    private ?int $num_tel2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialite = null;

    #[ORM\Column(nullable: true)]
    private ?int $validation = null;

    #[ORM\Column(nullable: true)]
    private ?int $rate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $disponibilite = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_c = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(maxSize: "5M", mimeTypes: ["image/jpeg", "image/png"], mimeTypesMessage: "Veuillez télécharger une image au format JPG ou PNG")]
    private ?string $diplomes = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dure_rdv = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $allergies = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $antecedent_maladie = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\NotBlank(message:"antecedent maladie must be non-empty")]
    private ?string $antecedent_medicaux = null;



    #[ORM\Column(nullable: true)]
    private ?float $log = null;

    #[ORM\Column(nullable: true)]
    private ?float $lat = null;

    #[ORM\Column(nullable: true)]
    private ?string $pays = null;

    #[ORM\Column(nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $groupe_sanguin = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;





    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }

    /**
     * @param mixed $confirm_password
     */
    public function setConfirmPassword($confirm_password): void
    {
        $this->confirm_password = $confirm_password;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getNumTel(): ?int
    {
        return $this->num_tel;
    }

    public function setNumTel(?int $num_tel): void
    {
        $this->num_tel = $num_tel;
    }

    public function getDateCreateCompte(): ?\DateTimeInterface
    {
        return $this->date_create_compte;
    }

    public function setDateCreateCompte(?\DateTimeInterface $date_create_compte): void
    {
        $this->date_create_compte = $date_create_compte;
    }

    public function getLastModifyPassword(): ?\DateTimeInterface
    {
        return $this->last_modify_password;
    }

    public function setLastModifyPassword(?\DateTimeInterface $last_modify_password): void
    {
        $this->last_modify_password = $last_modify_password;
    }

    public function getLastModifyData(): ?\DateTimeInterface
    {
        return $this->last_modify_data;
    }

    public function setLastModifyData(?\DateTimeInterface $last_modify_data): void
    {
        $this->last_modify_data = $last_modify_data;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): void
    {
        $this->date_naissance = $date_naissance;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getMaladieChronique(): ?string
    {
        return $this->maladie_chronique;
    }

    public function setMaladieChronique(?string $maladie_chronique): void
    {
        $this->maladie_chronique = $maladie_chronique;
    }

    public function getNumTel2(): ?int
    {
        return $this->num_tel2;
    }

    public function setNumTel2(?int $num_tel2): void
    {
        $this->num_tel2 = $num_tel2;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): void
    {
        $this->specialite = $specialite;
    }

    public function getValidation(): ?int
    {
        return $this->validation;
    }

    public function setValidation(?int $validation): void
    {
        $this->validation = $validation;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): void
    {
        $this->rate = $rate;
    }

    public function getDisponibilite(): ?\DateTimeInterface
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(?\DateTimeInterface $disponibilite): void
    {
        $this->disponibilite = $disponibilite;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): void
    {
        $this->date_debut = $date_debut;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): void
    {
        $this->date_fin = $date_fin;
    }

    public function getPrixC(): ?float
    {
        return $this->prix_c;
    }

    public function setPrixC(?float $prix_c): void
    {
        $this->prix_c = $prix_c;
    }

    public function getDiplomes(): ?string
    {
        return $this->diplomes;
    }

    public function setDiplomes(?string $diplomes): void
    {
        $this->diplomes = $diplomes;
    }

    public function getDureRdv(): ?\DateTimeInterface
    {
        return $this->dure_rdv;
    }

    public function setDureRdv(?\DateTimeInterface $dure_rdv): void
    {
        $this->dure_rdv = $dure_rdv;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): void
    {
        $this->allergies = $allergies;
    }

    public function getAntecedentMaladie(): ?string
    {
        return $this->antecedent_maladie;
    }

    public function setAntecedentMaladie(?string $antecedent_maladie): void
    {
        $this->antecedent_maladie = $antecedent_maladie;
    }

    public function getAntecedentMedicaux(): ?string
    {
        return $this->antecedent_medicaux;
    }

    public function setAntecedentMedicaux(?string $antecedent_medicaux): void
    {
        $this->antecedent_medicaux = $antecedent_medicaux;
    }

    public function getLog(): ?float
    {
        return $this->log;
    }

    public function setLog(?float $log): void
    {
        $this->log = $log;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): void
    {
        $this->pays = $pays;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): void
    {
        $this->ville = $ville;
    }

    public function getGroupeSanguin(): ?string
    {
        return $this->groupe_sanguin;
    }

    public function setGroupeSanguin(?string $groupe_sanguin): void
    {
        $this->groupe_sanguin = $groupe_sanguin;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

















    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;


        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): int
    {
        return (int)$this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**

     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }



    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $id_patient;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $id_medecin;

    #[ORM\OneToMany(mappedBy: 'expert', targetEntity: RendezVous::class)]
    private Collection $id_expert;



    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Reponse::class, orphanRemoval: true)]
    private Collection $reponses;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Reclamation::class, orphanRemoval: true)]
    private Collection $reclame;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Medicament::class)]
    private Collection $medicaments;



    public function __construct()
    {
        $this->id_patient = new ArrayCollection();
        $this->id_medecin = new ArrayCollection();
        $this->id_expert = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->reponses = new ArrayCollection();
        $this->reclame = new ArrayCollection();
        $this->medicaments = new ArrayCollection();

    }



    /**
     * @return Collection<int, RendezVous>
     */
    


#[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $id_patient;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: RendezVous::class, orphanRemoval: true)]
    private Collection $id_medecin;

    #[ORM\OneToMany(mappedBy: 'expert', targetEntity: RendezVous::class)]
    private Collection $id_expert;



    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Reponse::class, orphanRemoval: true)]
    private Collection $reponses;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Reclamation::class, orphanRemoval: true)]
    private Collection $reclame;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Medicament::class)]
    private Collection $medicaments;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Avis::class)]
    private Collection $avis;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Reclamation::class)]
    private Collection $reclamations;



    public function __construct()
{
    $this->id_patient = new ArrayCollection();
    $this->id_medecin = new ArrayCollection();
    $this->id_expert = new ArrayCollection();
    $this->questions = new ArrayCollection();
    $this->reponses = new ArrayCollection();
    $this->reclame = new ArrayCollection();
    $this->medicaments = new ArrayCollection();
    $this->avis = new ArrayCollection();
    $this->reclamations = new ArrayCollection();

}



    /**
     * @return Collection<int, RendezVous>
     */
    public function getIdPatient(): Collection
{
    return $this->id_patient;
}

    public function addIdPatient(RendezVous $idPatient): static
{
    if (!$this->id_patient->contains($idPatient)) {
        $this->id_patient->add($idPatient);
        $idPatient->setPatient($this);
    }

    return $this;
}

    public function removeIdPatient(RendezVous $idPatient): static
{
    if ($this->id_patient->removeElement($idPatient)) {
        // set the owning side to null (unless already changed)
        if ($idPatient->getPatient() === $this) {
            $idPatient->setPatient(null);
        }
    }

    return $this;
}

    /**
     * @return Collection<int, RendezVous>
     */
    public function getIdMedecin(): Collection
{
    return $this->id_medecin;
}

    public function addIdMedecin(RendezVous $idMedecin): static
{
    if (!$this->id_medecin->contains($idMedecin)) {
        $this->id_medecin->add($idMedecin);
        $idMedecin->setMedecin($this);
    }

    return $this;
}

    public function removeIdMedecin(RendezVous $idMedecin): static
{
    if ($this->id_medecin->removeElement($idMedecin)) {
        // set the owning side to null (unless already changed)
        if ($idMedecin->getMedecin() === $this) {
            $idMedecin->setMedecin(null);
        }
    }

    return $this;
}

    /**
     * @return Collection<int, RendezVous>
     */
    public function getIdExpert(): Collection
{
    return $this->id_expert;
}

    public function addIdExpert(RendezVous $idExpert): static
{
    if (!$this->id_expert->contains($idExpert)) {
        $this->id_expert->add($idExpert);
        $idExpert->setExpert($this);
    }

    return $this;
}

    public function removeIdExpert(RendezVous $idExpert): static
{
    if ($this->id_expert->removeElement($idExpert)) {
        // set the owning side to null (unless already changed)
        if ($idExpert->getExpert() === $this) {
            $idExpert->setExpert(null);
        }
    }

    return $this;
}



    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
{
    return $this->questions;
}

    public function addQuestion(Question $question): static
{
    if (!$this->questions->contains($question)) {
        $this->questions->add($question);
        $question->setPatient($this);
    }

    return $this;
}

    public function removeQuestion(Question $question): static
{
    if ($this->questions->removeElement($question)) {
        // set the owning side to null (unless already changed)
        if ($question->getPatient() === $this) {
            $question->setPatient(null);
        }
    }

    return $this;
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
        $reponse->setMedecin($this);
    }

    return $this;
}

    public function removeReponse(Reponse $reponse): static
{
    if ($this->reponses->removeElement($reponse)) {
        // set the owning side to null (unless already changed)
        if ($reponse->getMedecin() === $this) {
            $reponse->setMedecin(null);
        }
    }

    return $this;
}

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclame(): Collection
{
    return $this->reclame;
}

    public function addReclame(Reclamation $reclame): static
{
    if (!$this->reclame->contains($reclame)) {
        $this->reclame->add($reclame);
        $reclame->setMedecin($this);
    }

    return $this;
}

    public function removeReclame(Reclamation $reclame): static
{
    if ($this->reclame->removeElement($reclame)) {
        // set the owning side to null (unless already changed)
        if ($reclame->getMedecin() === $this) {
            $reclame->setMedecin(null);
        }
    }

    return $this;
}

    /**
     * @return Collection<int, Medicament>
     */
    public function getMedicaments(): Collection
{
    return $this->medicaments;
}

    public function addMedicament(Medicament $medicament): static
{
    if (!$this->medicaments->contains($medicament)) {
        $this->medicaments->add($medicament);
        $medicament->setUser($this);
    }

    return $this;
}

    public function removeMedicament(Medicament $medicament): static
{
    if ($this->medicaments->removeElement($medicament)) {
        // set the owning side to null (unless already changed)
        if ($medicament->getUser() === $this) {
            $medicament->setUser(null);
        }
    }

    return $this;
}

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setPatient($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getPatient() === $this) {
                $avi->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setPatient($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getPatient() === $this) {
                $reclamation->setPatient(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->id;
    }


}
