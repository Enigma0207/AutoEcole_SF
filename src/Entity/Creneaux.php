<?php

namespace App\Entity;

use App\Repository\CreneauxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreneauxRepository::class)]
class Creneaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $isAvailable = true;

    #[ORM\ManyToOne(inversedBy: 'creneauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'creneauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Permis $permis = null;

    #[ORM\ManyToOne(inversedBy: 'creneauxess')]
    private ?User $user_Eleve = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPermis(): ?Permis
    {
        return $this->permis;
    }

    public function setPermis(?Permis $permis): static
    {
        $this->permis = $permis;

        return $this;
    }

    public function getUserEleve(): ?User
    {
        return $this->user_Eleve;
    }

    public function setUserEleve(?User $user_Eleve): static
    {
        $this->user_Eleve = $user_Eleve;

        return $this;
    }

    
}
