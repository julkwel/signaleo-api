<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

/**
 * Class Todo.
 *
 * @ORM\Entity()
 */
class Todo
{
    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string|null $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean $isDone
     *
     * @ORM\Column(type="boolean")
     */
    private $isDone;
    
    public function __construct()
    {
        $this->isDone = false;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Todo
     */
    public function setTitle(string $title): Todo
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Todo
     */
    public function setDescription(?string $description): Todo
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return boolean|null
     */
    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    /**
     * @param boolean $isDone
     *
     * @return $this
     */
    public function setIsDone(bool $isDone): Todo
    {
        $this->isDone=$isDone;

        return $this;
    }
}