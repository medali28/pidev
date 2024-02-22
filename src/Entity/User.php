<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column]
    private ?int $role = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    #[ORM\Column]
    private ?int $num_tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_create_compte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $last_modify_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $last_modify_data = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_naissance = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $maladie_chronique = null;

    #[ORM\Column]
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
    private ?string $diplomes = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dure_rdv = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $allergies = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $antecedent_maladie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $antecedent_medicaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $groupe_sanguin = null;

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


    public function __toString(): string
    {
        return $this->id;
        // TODO: Implement __toString() method.
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->num_tel;
    }

    public function setNumTel(int $num_tel): static
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getDateCreateCompte(): ?\DateTimeInterface
    {
        return $this->date_create_compte;
    }

    public function setDateCreateCompte(\DateTimeInterface $date_create_compte): static
    {
        $this->date_create_compte = $date_create_compte;

        return $this;
    }

    public function getLastModifyDate(): ?\DateTimeInterface
    {
        return $this->last_modify_date;
    }

    public function setLastModifyDate(\DateTimeInterface $last_modify_date): static
    {
        $this->last_modify_date = $last_modify_date;

        return $this;
    }

    public function getLastModifyData(): ?\DateTimeInterface
    {
        return $this->last_modify_data;
    }

    public function setLastModifyData(\DateTimeInterface $last_modify_data): static
    {
        $this->last_modify_data = $last_modify_data;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): static
    {
        $this->date_naissance = $date_naissance;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getMaladieChronique(): ?string
    {
        return $this->maladie_chronique;
    }

    public function setMaladieChronique(?string $maladie_chronique): static
    {
        $this->maladie_chronique = $maladie_chronique;

        return $this;
    }

    public function getNumTel2(): ?int
    {
        return $this->num_tel2;
    }

    public function setNumTel2(int $num_tel2): static
    {
        $this->num_tel2 = $num_tel2;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getValidation(): ?int
    {
        return $this->validation;
    }

    public function setValidation(?int $validation): static
    {
        $this->validation = $validation;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDisponibilite(): ?\DateTimeInterface
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(?\DateTimeInterface $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getPrixC(): ?float
    {
        return $this->prix_c;
    }

    public function setPrixC(?float $prix_c): static
    {
        $this->prix_c = $prix_c;

        return $this;
    }

    public function getDiplomes(): ?string
    {
        return $this->diplomes;
    }

    public function setDiplomes(?string $diplomes): static
    {
        $this->diplomes = $diplomes;

        return $this;
    }

    public function getDureRdv(): ?\DateTimeInterface
    {
        return $this->dure_rdv;
    }

    public function setDureRdv(?\DateTimeInterface $dure_rdv): static
    {
        $this->dure_rdv = $dure_rdv;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(?string $allergies): static
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getAntecedentMaladie(): ?string
    {
        return $this->antecedent_maladie;
    }

    public function setAntecedentMaladie(?string $antecedent_maladie): static
    {
        $this->antecedent_maladie = $antecedent_maladie;

        return $this;
    }

    public function getAntecedentMedicaux(): ?string
    {
        return $this->antecedent_medicaux;
    }

    public function setAntecedentMedicaux(?string $antecedent_medicaux): static
    {
        $this->antecedent_medicaux = $antecedent_medicaux;

        return $this;
    }

    public function getGroupeSanguin(): ?string
    {
        return $this->groupe_sanguin;
    }

    public function setGroupeSanguin(?string $groupe_sanguin): static
    {
        $this->groupe_sanguin = $groupe_sanguin;

        return $this;
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










}
