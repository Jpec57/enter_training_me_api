<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\ExerciseReferenceRepository;

/**
 * @ORM\Entity(repositoryClass=ExerciseReferenceRepository::class)
 * @UniqueEntity("name")
 */

#[UniqueEntity('name')]
#[ApiResource(
    itemOperations: ["get" => [
        'denormalizationContext' => ['groups' => ['default', 'exercise_reference_muscle_activation']],
    ]],
    denormalizationContext: ['groups' => ['default', 'exercise_reference_muscle_activation']],
    normalizationContext: ['groups' => ['default', 'exercise_reference_muscle_activation']],
)]
class ExerciseReference
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $reference;

    /**
     * @Groups({"default", "summary"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[Assert\NotBlank]
    private $name;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="array", nullable=true)
     */
    private $material = [];

    /**
     * @Groups({"default"})
     * @ORM\Column(type="float")
     */
    private $strainessFactor;

    /**
     * @Groups({"exercise_reference_muscle_activation", "default"})
     * @ORM\OneToMany(targetEntity=MuscleActivation::class, mappedBy="exerciseReference", cascade={"persist"})
     */
    private $muscleActivations;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isBodyweightExercise = false;

    public function __construct()
    {
        $this->muscleActivations = new ArrayCollection();
        $this->isBodyweightExercise = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMaterial(): ?array
    {
        return $this->material;
    }

    public function setMaterial(?array $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getStrainessFactor(): ?float
    {
        return $this->strainessFactor;
    }

    public function setStrainessFactor(float $strainessFactor): self
    {
        $this->strainessFactor = $strainessFactor;

        return $this;
    }

    /**
     * @return Collection|MuscleActivation[]
     */
    public function getMuscleActivations(): Collection
    {
        return $this->muscleActivations;
    }

    public function addMuscleActivation(MuscleActivation $muscleActivation): self
    {
        if (!$this->muscleActivations->contains($muscleActivation)) {
            $this->muscleActivations[] = $muscleActivation;
            $muscleActivation->setExerciseReference($this);
        }

        return $this;
    }

    public function removeMuscleActivation(MuscleActivation $muscleActivation): self
    {
        if ($this->muscleActivations->removeElement($muscleActivation)) {
            // set the owning side to null (unless already changed)
            if ($muscleActivation->getExerciseReference() === $this) {
                $muscleActivation->setExerciseReference(null);
            }
        }

        return $this;
    }

    public function getIsBodyweightExercise(): ?bool
    {
        return $this->isBodyweightExercise;
    }

    public function setIsBodyweightExercise(bool $isBodyweightExercise): self
    {
        $this->isBodyweightExercise = $isBodyweightExercise;

        return $this;
    }
}
