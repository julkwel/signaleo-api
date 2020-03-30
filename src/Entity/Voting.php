<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VotingRepository")
 */
class Voting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAdd;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Actualite", inversedBy="vote")
     */
    private $actualite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="votings")
     */
    private $user;

    /**
     * Voting constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dateAdd = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getActualite(): ?Actualite
    {
        return $this->actualite;
    }

    public function setActualite(?Actualite $actualite): self
    {
        $this->actualite = $actualite;

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
}
