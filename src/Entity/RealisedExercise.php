<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RealisedExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RealisedExerciseRepository::class)
 */
#[ApiResource(
    denormalizationContext: ['groups' => ['default',
    'realised_exercise_set', 'realised_exercise_execution_style',
     'realised_exercise_exercise_reference']],
)]
class RealisedExercise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"realised_exercise_exercise_reference"})
     * @ORM\ManyToOne(targetEntity=ExerciseReference::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $exerciseReference;

    /**
     * @Groups({"realised_exercise_set"})
     * @ORM\OneToMany(targetEntity=Set::class, mappedBy="realisedExercise", cascade={"persist"})
     */
    private $sets;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    private $restBetweenSet;

    /**
     * @Groups({"realised_exercise_execution_style"}) 
     * @ORM\ManyToOne(targetEntity=ExecutionStyle::class)
     */
    private $executionStyle;

    /**
     * @Groups({"realised_exercise_exercise_cycle"}) 
     * @ORM\ManyToOne(targetEntity=ExerciseCycle::class, inversedBy="exercises")
     */
    private $exerciseCycle;

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

    public function getExerciseCycle(): ?ExerciseCycle
    {
        return $this->exerciseCycle;
    }

    public function setExerciseCycle(?ExerciseCycle $exerciseCycle): self
    {
        $this->exerciseCycle = $exerciseCycle;

        return $this;
    }
}
