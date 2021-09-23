<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RealisedExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RealisedExerciseRepository::class)
 */
#[ApiResource]
class RealisedExercise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ExerciseReference::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $exerciseReference;

    /**
     * @ORM\OneToMany(targetEntity=Set::class, mappedBy="realisedExercise")
     */
    private $sets;

    /**
     * @ORM\Column(type="integer")
     */
    private $restBetweenSet;

    /**
     * @ORM\ManyToOne(targetEntity=ExecutionStyle::class)
     */
    private $executionStyle;

    /**
     * @ORM\ManyToOne(targetEntity=ExerciceCycle::class, inversedBy="exercises")
     */
    private $exerciceCycle;

    public function __construct()
    {
        $this->sets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExerciseReference(): ?ExerciseReference
    {
        return $this->exerciseReference;
    }

    public function setExerciseReference(?ExerciseReference $exerciseReference): self
    {
        $this->exerciseReference = $exerciseReference;

        return $this;
    }

    /**
     * @return Collection|Set[]
     */
    public function getSets(): Collection
    {
        return $this->sets;
    }

    public function addSet(Set $set): self
    {
        if (!$this->sets->contains($set)) {
            $this->sets[] = $set;
            $set->setRealisedExercise($this);
        }

        return $this;
    }

    public function removeSet(Set $set): self
    {
        if ($this->sets->removeElement($set)) {
            // set the owning side to null (unless already changed)
            if ($set->getRealisedExercise() === $this) {
                $set->setRealisedExercise(null);
            }
        }

        return $this;
    }

    public function getRestBetweenSet(): ?int
    {
        return $this->restBetweenSet;
    }

    public function setRestBetweenSet(int $restBetweenSet): self
    {
        $this->restBetweenSet = $restBetweenSet;

        return $this;
    }

    public function getExecutionStyle(): ?ExecutionStyle
    {
        return $this->executionStyle;
    }

    public function setExecutionStyle(?ExecutionStyle $executionStyle): self
    {
        $this->executionStyle = $executionStyle;

        return $this;
    }

    public function getExerciceCycle(): ?ExerciceCycle
    {
        return $this->exerciceCycle;
    }

    public function setExerciceCycle(?ExerciceCycle $exerciceCycle): self
    {
        $this->exerciceCycle = $exerciceCycle;

        return $this;
    }
}
