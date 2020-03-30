<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SocieteRepository")
 */
class Societe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $responsable;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avis", mappedBy="societe")
     */
    private $stars;

    public function __construct()
    {
        $this->stars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(?string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getStars(): Collection
    {
        return $this->stars;
    }

    public function addStar(Avis $star): self
    {
        if (!$this->stars->contains($star)) {
            $this->stars[] = $star;
            $star->setSociete($this);
        }

        return $this;
    }

    public function removeStar(Avis $star): self
    {
        if ($this->stars->contains($star)) {
            $this->stars->removeElement($star);
            // set the owning side to null (unless already changed)
            if ($star->getSociete() === $this) {
                $star->setSociete(null);
            }
        }

        return $this;
    }
}
