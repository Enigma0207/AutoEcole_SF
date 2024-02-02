<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Form\UserFormType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?string $phone = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: "json")]
    private array $roles = ['ROLE_ELEVE'];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Creneaux::class)]
    private Collection $creneauxes;

    #[ORM\OneToMany(mappedBy: 'user_Eleve', targetEntity: Creneaux::class)]
    private Collection $creneauxess; // Rôle par défaut

    public function __construct()
    {
        $this->roles = $this->roles ?: ['ROLE_ELEVE'];
        $this->creneauxes = new ArrayCollection();
        $this->creneauxess = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
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

    public function eraseCredentials()
    {
        // Supprimer les données sensibles, comme les mots de passe stockés temporairement
        //$this->password = null;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = array_unique($roles);

        return $this;
    }



    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        // Vous n'avez pas besoin de sel si vous utilisez l'algorithme bcrypt
        return null;
    }

    /**
     * @return Collection<int, Creneaux>
     */
    public function getCreneauxes(): Collection
    {
        return $this->creneauxes;
    }

    public function addCreneaux(Creneaux $creneaux): static
    {
        if (!$this->creneauxes->contains($creneaux)) {
            $this->creneauxes->add($creneaux);
            $creneaux->setUser($this);
        }

        return $this;
    }

    public function removeCreneaux(Creneaux $creneaux): static
    {
        if ($this->creneauxes->removeElement($creneaux)) {
            // set the owning side to null (unless already changed)
            if ($creneaux->getUser() === $this) {
                $creneaux->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Creneaux>
     */
    public function getCreneauxess(): Collection
    {
        return $this->creneauxess;
    }

    public function addCreneauxess(Creneaux $creneauxess): static
    {
        if (!$this->creneauxess->contains($creneauxess)) {
            $this->creneauxess->add($creneauxess);
            $creneauxess->setUserEleve($this);
        }

        return $this;
    }

    public function removeCreneauxess(Creneaux $creneauxess): static
    {
        if ($this->creneauxess->removeElement($creneauxess)) {
            // set the owning side to null (unless already changed)
            if ($creneauxess->getUserEleve() === $this) {
                $creneauxess->setUserEleve(null);
            }
        }

        return $this;
    }


  
}
