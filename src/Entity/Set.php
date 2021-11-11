<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SetRepository::class)
 * @ORM\Table(name="`set`")
 */
#[ApiResource(
    denormalizationContext: ['groups' => ['default']],
    normalizationContext: ['groups' => ['default']],
)]
class Set
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @Groups({"default", "summary", "performance"})
     * @ORM\Column(type="integer")
     */
    private $reps;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $weightPercent;

    /**
     * @Groups({"default", "summary", "performance"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @Groups({"set_exercise_format"})
     * @ORM\ManyToOne(targetEntity=ExerciseFormat::class, inversedBy="predefinedSets")
     */
    private $exerciseFormat;

    /**
     * @Groups({"set_realised_exercise"})
     * @ORM\ManyToOne(targetEntity=RealisedExercise::class, inversedBy="sets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $realisedExercise;

    /**
     * @Groups({"performance"})
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * @Groups({"default", "performance"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $realisedDate;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRealisedDate(): ?int
    {
        return $this->realisedDate;
    }

    public function setRealisedDate(?int $realisedDate): self
    {
        $this->realisedDate = $realisedDate;

        return $this;
    }
}
