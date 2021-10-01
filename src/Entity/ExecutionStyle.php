<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExecutionStyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ExecutionStyleRepository::class)
 */
#[ApiResource]
#[UniqueEntity("name")]
class ExecutionStyle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $strainFactor;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ExerciseFormat::class, mappedBy="executionStyle")
     */
    private $exerciseFormats;

    public function __construct()
    {
        $this->exerciseFormats = new ArrayCollection();
    }

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

    public function getStrainFactor(): ?float
    {
        return $this->strainFactor;
    }

    public function setStrainFactor(float $strainFactor): self
    {
        $this->strainFactor = $strainFactor;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ExerciseFormat[]
     */
    public function getExerciseFormats(): Collection
    {
        return $this->exerciseFormats;
    }

    public function addExerciseFormat(ExerciseFormat $exerciseFormat): self
    {
        if (!$this->exerciseFormats->contains($exerciseFormat)) {
            $this->exerciseFormats[] = $exerciseFormat;
            $exerciseFormat->setExecutionStyle($this);
        }

        return $this;
    }

    public function removeExerciseFormat(ExerciseFormat $exerciseFormat): self
    {
        if ($this->exerciseFormats->removeElement($exerciseFormat)) {
            // set the owning side to null (unless already changed)
            if ($exerciseFormat->getExecutionStyle() === $this) {
                $exerciseFormat->setExecutionStyle(null);
            }
        }

        return $this;
    }
}
