<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Interfaces\SummarizableEntityInterface;
use App\Repository\RealisedExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=RealisedExerciseRepository::class)
 */
#[ApiResource(
    denormalizationContext: ['groups' => [
        'default',
        'realised_exercise_set', 'realised_exercise_execution_style', 'training',
        'realised_exercise_exercise_reference'
    ]],
    normalizationContext: ['groups' => [
        'default', 'training',
        'realised_exercise_set', 'realised_exercise_execution_style',
        'realised_exercise_exercise_reference'
    ]]
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
     * @Groups({"realised_exercise_exercise_reference", "summary"})
     * @ORM\ManyToOne(targetEntity=ExerciseReference::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $exerciseReference;

    /**
     * @Groups({"realised_exercise_set", "summary"})
     * @ORM\OneToMany(targetEntity=Set::class, mappedBy="realisedExercise", cascade={"persist", "remove"})
     */
    #[ApiSubresource]
    private $sets;

    /**
     * @Groups({"default", "summary"})
     * @ORM\Column(type="integer")
     */
    private $restBetweenSet;

    /**
     * @Groups({"realised_exercise_execution_style", "summary", "training"}) 
     * @ORM\ManyToOne(targetEntity=ExecutionStyle::class)
     */
    private $executionStyle;

    /**
     * @ORM\ManyToOne(targetEntity=Training::class, inversedBy="exercises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $training;

    /**
     * @Groups({"default", "summary"})
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isIsometric = false;


    public function __construct()
    {
        $this->sets = new ArrayCollection();
    }

    /**
     * @Groups({"default", "summary"})
     */
    public function getIntensity(): float
    {
        $nbSets = count($this->sets);
        $difficulty = $this->exerciseReference->getStrainessFactor();

        $intensity = 0;
        foreach ($this->sets as $set) {
            $intensity += (1 + $set->getWeight() ?? 70) * $set->getReps();
        }
        return $difficulty * $intensity;
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

    public function getTimeUnderTension(): string
    {
        return $this->executionStyle?->getTotalTimeUnderTension() ?? ExecutionStyle::DEFAULT_EXECUTION_TEMPO;
    }

    public function getTimeUnderTensionAsArray(): array
    {
        return explode("/", $this->getTimeUnderTension());
    }

    public function getTotalTimeUnderTension(): int
    {
        $total = 0;
        foreach ($this->getTimeUnderTensionAsArray() as $val) {
            $total += $val;
        }

        return $total;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): self
    {
        $this->training = $training;

        return $this;
    }

    public function getIsIsometric(): ?bool
    {
        return $this->isIsometric;
    }

    public function setIsIsometric(bool $isIsometric): self
    {
        $this->isIsometric = $isIsometric;

        return $this;
    }
}
