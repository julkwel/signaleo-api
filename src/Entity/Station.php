<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StationRepository")
 */
class Station
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
    private $distributeur;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $province;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $commune;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $localites;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $nomStation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAdd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDistributeur(): ?string
    {
        return $this->distributeur;
    }

    public function setDistributeur(?string $distributeur): self
    {
        $this->distributeur = $distributeur;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(?string $commune): self
    {
        $this->commune = $commune;

        return $this;
    }

    public function getLocalites(): ?string
    {
        return $this->localites;
    }

    public function setLocalites(string $localites): self
    {
        $this->localites = $localites;

        return $this;
    }

    public function getNomStation(): ?string
    {
        return $this->nomStation;
    }

    public function setNomStation(?string $nomStation): self
    {
        $this->nomStation = $nomStation;

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
}
