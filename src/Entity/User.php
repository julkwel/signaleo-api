<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true,unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pseudo;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateAdd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Actualite", mappedBy="user")
     */
    private $emboutekas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ZaMbaEnto", mappedBy="user")
     */
    private $zaMbaEntos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offre", mappedBy="user")
     */
    private $offres;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Voting", mappedBy="user", cascade={"persist", "remove"})
     */
    private $voting;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voting", mappedBy="user")
     */
    private $votings;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text",nullable=true)
     */
    private $avatar;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user")
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Friends", mappedBy="userFriends")
     */
    private $friends;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $point;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avis", mappedBy="user")
     */
    private $avis;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * User constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dateAdd = new DateTime('now');
        $this->emboutekas = new ArrayCollection();
        $this->zaMbaEntos = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->votings = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Actualite[]
     */
    public function getEmboutekas(): Collection
    {
        return $this->emboutekas;
    }

    public function addEmbouteka(Actualite $embouteka): self
    {
        if (!$this->emboutekas->contains($embouteka)) {
            $this->emboutekas[] = $embouteka;
            $embouteka->setUser($this);
        }

        return $this;
    }

    public function removeEmbouteka(Actualite $embouteka): self
    {
        if ($this->emboutekas->contains($embouteka)) {
            $this->emboutekas->removeElement($embouteka);
            // set the owning side to null (unless already changed)
            if ($embouteka->getUser() === $this) {
                $embouteka->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ZaMbaEnto[]
     */
    public function getZaMbaEntos(): Collection
    {
        return $this->zaMbaEntos;
    }

    public function addZaMbaEnto(ZaMbaEnto $zaMbaEnto): self
    {
        if (!$this->zaMbaEntos->contains($zaMbaEnto)) {
            $this->zaMbaEntos[] = $zaMbaEnto;
            $zaMbaEnto->setUser($this);
        }

        return $this;
    }

    public function removeZaMbaEnto(ZaMbaEnto $zaMbaEnto): self
    {
        if ($this->zaMbaEntos->contains($zaMbaEnto)) {
            $this->zaMbaEntos->removeElement($zaMbaEnto);
            // set the owning side to null (unless already changed)
            if ($zaMbaEnto->getUser() === $this) {
                $zaMbaEnto->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setUser($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->contains($offre)) {
            $this->offres->removeElement($offre);
            // set the owning side to null (unless already changed)
            if ($offre->getUser() === $this) {
                $offre->setUser(null);
            }
        }

        return $this;
    }

    public function getVoting(): ?Voting
    {
        return $this->voting;
    }

    public function setVoting(Voting $voting): self
    {
        $this->voting = $voting;

        // set the owning side of the relation if necessary
        if ($voting->getUser() !== $this) {
            $voting->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Voting[]
     */
    public function getVotings(): Collection
    {
        return $this->votings;
    }

    public function addVoting(Voting $voting): self
    {
        if (!$this->votings->contains($voting)) {
            $this->votings[] = $voting;
            $voting->setUser($this);
        }

        return $this;
    }

    public function removeVoting(Voting $voting): self
    {
        if ($this->votings->contains($voting)) {
            $this->votings->removeElement($voting);
            // set the owning side to null (unless already changed)
            if ($voting->getUser() === $this) {
                $voting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     *
     * @return User
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     *
     * @return User
     */
    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friends[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friends $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
            $friend->setUserFriends($this);
        }

        return $this;
    }

    public function removeFriend(Friends $friend): self
    {
        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
            // set the owning side to null (unless already changed)
            if ($friend->getUserFriends() === $this) {
                $friend->setUserFriends(null);
            }
        }

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(?int $point): self
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setUser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->contains($avi)) {
            $this->avis->removeElement($avi);
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}
