<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SetRepository::class)
 * @ORM\Table(name="`set`")
 */
#[ApiResource]
class Set
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reps;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weightPercent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\ManyToOne(targetEntity=ExerciseFormat::class, inversedBy="predefinedSets")
     */
    private $exerciseFormat;

    /**
     * @ORM\ManyToOne(targetEntity=RealisedExercise::class, inversedBy="sets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $realisedExercise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(int $reps): self
    {
        $this->reps = $reps;

        return $this;
    }

    public function getWeightPercent(): ?float
    {
        return $this->weightPercent;
    }

    public function setWeightPercent(?float $weightPercent): self
    {
        $this->weightPercent = $weightPercent;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getExerciseFormat(): ?ExerciseFormat
    {
        return $this->exerciseFormat;
    }

    public function setExerciseFormat(?ExerciseFormat $exerciseFormat): self
    {
        $this->exerciseFormat = $exerciseFormat;

        return $this;
    }

    public function getRealisedExercise(): ?RealisedExercise
    {
        return $this->realisedExercise;
    }

    public function setRealisedExercise(?RealisedExercise $realisedExercise): self
    {
        $this->realisedExercise = $realisedExercise;

        return $this;
    }
}
