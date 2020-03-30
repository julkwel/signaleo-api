<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ZaMbaEntoRepository")
 */
class ZaMbaEnto
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $depart;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $arrive;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDepart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAdd;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="zaMbaEntos")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $contact;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string",length=150,nullable=true)
     */
    private $lieuExact;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string",length=10,nullable=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string",length=10,nullable=true)
     */
    private $preference;

    /**
     * ZaMbaEnto constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dateAdd = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(?\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    /**
     * @return string|null
     */
    public function getLieuExact(): ?string
    {
        return $this->lieuExact;
    }

    /**
     * @param string|null $lieuExact
     *
     * @return ZaMbaEnto
     */
    public function setLieuExact(?string $lieuExact): self
    {
        $this->lieuExact = $lieuExact;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string|null $nombre
     *
     * @return ZaMbaEnto
     */
    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return  $this;
    }

    /**
     * @return string|null
     */
    public function getPreference(): ?string
    {
        return $this->preference;
    }

    /**
     * @param string|null $preference
     *
     * @return ZaMbaEnto
     */
    public function setPreference(?string $preference): self
    {
        $this->preference = $preference;

        return $this;
    }
}
