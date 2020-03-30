<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FokontanyRepository")
 */
class Fokontany
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
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string",length=100)
     */
    private $commune;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommune(): string
    {
        return $this->commune;
    }

    /**
     * @param string $commune
     *
     * @return Fokontany
     */
    public function setCommune(string $commune): self
    {
        $this->commune = $commune;

        return $this;
    }
}
