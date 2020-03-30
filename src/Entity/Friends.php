<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FriendsRepository")
 */
class Friends
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="friends")
     */
    private $userFriends;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAccepted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAdd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAccepted;

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

    public function getUserFriends(): ?User
    {
        return $this->userFriends;
    }

    public function setUserFriends(?User $userFriends): self
    {
        $this->userFriends = $userFriends;

        return $this;
    }

    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(?bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

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

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateAccepted(): ?\DateTimeInterface
    {
        return $this->dateAccepted;
    }

    /**
     * @param mixed $dateAccepted
     *
     * @return Friends
     */
    public function setDateAccepted($dateAccepted): self
    {
        $this->dateAccepted = $dateAccepted;

        return  $this;
    }
}
