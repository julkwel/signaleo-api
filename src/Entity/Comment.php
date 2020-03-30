<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdd;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Actualite", inversedBy="comments")
     */
    private $Actualite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="comments", cascade={"persist", "remove"})
     */
    private $responses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="responses", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * Comment constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dateAdd = new \DateTime('now');
        $this->comments = new ArrayCollection();
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeInterface $dateAdd): self
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getActualite(): ?Actualite
    {
        return $this->Actualite;
    }

    public function setActualite(?Actualite $Actualite): self
    {
        $this->Actualite = $Actualite;

        return $this;
    }

    public function getResponses(): ?self
    {
        return $this->responses;
    }

    public function setResponses(?self $responses): self
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setResponses($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getResponses() === $this) {
                $comment->setResponses(null);
            }
        }

        return $this;
    }
}
