<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffreRepository")
 */
class Offre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offres")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $depart;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $arrive;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $frais;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nombreDePlace;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDispo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAdd;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDepart;

    public function __construct()
    {
        $this->dateAdd = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): self
    {
        $this->depart = $depart;

        return $this;
    }

    public function getArrive(): ?string
    {
        return $this->arrive;
    }

    public function setArrive(string $arrive): self
    {
        $this->arrive = $arrive;

        return $this;
    }

    public function getFrais(): ?string
    {
        return $this->frais;
    }

    public function setFrais(?string $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getNombreDePlace(): ?string
    {
        return $this->nombreDePlace;
    }

    public function setNombreDePlace(?string $nombreDePlace): self
    {
        $this->nombreDePlace = $nombreDePlace;

        return $this;
    }

    public function getIsDispo(): ?bool
    {
        return $this->isDispo;
    }

    public function setIsDispo(?bool $isDispo): self
    {
        $this->isDispo = $isDispo;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(?\DateTimeInterface $dateAdd): self
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(?\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }
}
