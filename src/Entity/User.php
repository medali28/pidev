<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message:"email invalid format")]
    #[Assert\NotBlank(message:" email must be non-empty")]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:"password must be non-empty")]
    private ?string $password = null;
    #[Assert\EqualTo(propertyPath:"password", message:"You did not type the same password")]
    public $confirm_password;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"First name must be non-empty")]
    private ?string $first_name = null;

    #[ORM\Column(length: 255 , )]
    #[Assert\NotBlank(message:"Last name must be non-empty")]
    private ?string $last_name = null;

    #[ORM\Column(length: 255 , nullable: true)]
    private ?string $gender = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Tel must be non-empty")]
    #[Assert\Length(min:8 ,minMessage: "minimum 8 chiffres")]
    private ?int $num_tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE , nullable: true)]
    private ?\DateTimeInterface $date_create_compte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE , nullable: true)]
    private ?\DateTimeInterface $last_modify_password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE , nullable: true)]
    private ?\DateTimeInterface $last_modify_data = null;

    #[ORM\Column(type: Types::DATE_MUTABLE , nullable: true)]
//    #[Assert\NotBlank(message:"date de naissance must be non-empty")]
//    #[Assert\LessThanOrEqual(new \DateTimeImmutable(), message:"Date of birth cannot be in the future")]
    private ?\DateTimeInterface $date_naissance = null;

    #[Assert\File( maxSize:"1M" , mimeTypes: ["image/jpeg", "image/png"] ,  mimeTypesMessage:"Veuillez télécharger une image au format JPG ou PNG" ) ]
//    #[Assert\NotBlank(message:"image must be non-empty")]
    #[ORM\Column(  length: 255 , nullable: true)]
    private ?string $image;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $maladie_chronique = null;

    #[ORM\Column(nullable: true)]
//    #[Assert\NotBlank(message:"Tel must be non-empty")]
    private ?int $num_tel2 = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\NotBlank(message:"Specialite must be non-empty")]
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
//    #[Assert\NotBlank(message:"prix must be non-empty")]
//    #[Assert\Positive(message:"prix must be positive")]
    private ?float $prix_c = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File( maxSize:"5M" , mimeTypes: ["image/jpeg", "image/png"] ,  mimeTypesMessage:"Veuillez télécharger une image au format JPG ou PNG" ) ]
//    #[Assert\NotBlank(message:"images must be non-empty")]
    private ?string $diplomes = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
//    #[Assert\NotBlank(message:"duree de rendez-vous must be non-empty")]
    private ?\DateTimeInterface $dure_rdv = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\NotBlank(message:"allergies must be non-empty")]
    private ?string $allergies = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\NotBlank(message:"antecedent maladie must be non-empty")]
    private ?string $antecedent_maladie = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\NotBlank(message:"antecedent maladie must be non-empty")]
    private ?string $antecedent_medicaux = null;

    #[ORM\Column(type: "string" , nullable: true)]
    private $reset_token;

    #[ORM\Column( nullable: true)]
    private ?float $log = null;

    #[ORM\Column( nullable: true)]
    private ?float $lat = null;

    #[ORM\Column( nullable: true)]
    private ?string $pays = null;

    #[ORM\Column( nullable: true)]
    private ?string $ville = null;

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


    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * @param mixed $reset_token
     */
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $groupe_sanguin = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

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
        return (int) $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function getGroupeSanguin(): ?string
    {
        return $this->groupe_sanguin;
    }

    public function setGroupeSanguin(?string $groupe_sanguin): void
    {
        $this->groupe_sanguin = $groupe_sanguin;
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }


    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
