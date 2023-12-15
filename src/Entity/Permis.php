<?php

namespace App\Entity;

use App\Repository\PermisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermisRepository::class)]
class Permis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'permis', targetEntity: Creneaux::class)]
    private Collection $creneauxes;

    public function __construct()
    {
        $this->creneauxes = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    // Dans votre classe Permis (App\Entity\Permis)
     // Dans votre classe User (App\Entity\User)
    public function __toString()
    {
        return $this->getType(); // ou toute autre propriété que vous souhaitez afficher
    }

    /**
     * @return Collection<int, Creneaux>
     */
    public function getcreneauxes(): Collection
    {
        return $this->creneauxes;
    }

    public function addcreneauxes(Creneaux $creneauxes): static
    {
        if (!$this->creneauxes->contains($creneauxes)) {
            $this->creneauxes->add($creneauxes);
            $creneauxes->setPermis($this);
        }

        return $this;
    }

    public function removecreneauxes(Creneaux $creneauxes): static
    {
        if ($this->creneauxes->removeElement($creneauxes)) {
            // set the owning side to null (unless already changed)
            if ($creneauxes->getPermis() === $this) {
                $creneauxes->setPermis(null);
            }
        }

        return $this;
    }


}
