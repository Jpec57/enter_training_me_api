<?php

namespace App\Entity;

use App\Repository\MuscleActivationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MuscleActivationRepository::class)
 */
class MuscleActivation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private $muscle;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="float")
     */
    private $activationRatio;

    /**
     * @ORM\ManyToOne(targetEntity=ExerciseReference::class, inversedBy="muscleActivations")
     */
    private $exerciseReference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMuscle(): ?string
    {
        return $this->muscle;
    }

    public function setMuscle(string $muscle): self
    {
        $this->muscle = $muscle;

        return $this;
    }

    public function getActivationRatio(): ?float
    {
        return $this->activationRatio;
    }

    public function setActivationRatio(float $activationRatio): self
    {
        $this->activationRatio = $activationRatio;

        return $this;
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
}
