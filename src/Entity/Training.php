<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\TrainingController;
use App\Controller\TrainingPublishController;
use App\Interfaces\SummarizableEntityInterface;
use App\Repository\TrainingRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=TrainingRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
#[ApiResource(
    itemOperations: [
        "get" => [
            'denormalization_context' => ['groups' => ['training', 'exercise_reference_muscle_activation', 'default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']],
        ],
        "patch" => [
            "security" => "is_granted('ROLE_ADMIN') or object.author == user",
            'denormalization_context' => ['groups' => ['training', 'exercise_reference_muscle_activation', 'default',  'summary', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']],
        ],
        "delete" => [
            "security" => "is_granted('ROLE_ADMIN') or object.author == user",
            'denormalization_context' => ['groups' => ['training', 'exercise_reference_muscle_activation', 'default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']],
        ],
        "publish" => [
            'method' => "POST",
            "path" => "/trainings/{id}/publish",
            "controller" => TrainingPublishController::class,
            "security" => "is_granted('ROLE_ADMIN') or object.author == user",
            "openapi_context" => [
                "summary" => "Permet de publier un entrainement",
            ]
        ]
    ],
    //Use to read / GET
    normalizationContext: ['groups' => ['training', 'exercise_reference_muscle_activation', 'default', 'summary', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']],
    //USE TO WRITE
    denormalizationContext: ['groups' => ['training', 'exercise_reference_muscle_activation', 'default',  'summary', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']],
)]
class Training
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"default", "summary"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"training_user"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trainings")
     */
    public $author;

    /**
     * @Groups({"training_exercise_cycle", "summary", "training"})
     * @ORM\OneToMany(targetEntity=ExerciseCycle::class, mappedBy="training", cascade={"persist", "remove"})
     */
    #[ApiSubresource]
    private $cycles;

    /**
     * @Groups({"default", "summary"})
     * @ORM\Column(type="integer")
     */
    private $restBetweenCycles;

    /**
     * @ORM\OneToMany(targetEntity=SavedTraining::class, mappedBy="trainingReference", cascade={"persist", "remove"})
     */
    private $savedTrainings;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isOfficial;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity=Training::class, inversedBy="realisedTrainings")
     */
    private $reference;

    /**
     * @Groups({"training_realised_training"})
     * @ORM\OneToMany(targetEntity=Training::class, mappedBy="reference")
     */
    private $realisedTrainings;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="datetime", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="datetime", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $hasBeenRealisedOnceByUser;

    public function __construct()
    {
        $this->cycles = new ArrayCollection();
        $this->savedTrainings = new ArrayCollection();
        $this->isOfficial = false;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->hasBeenRealisedOnceByUser = true;
        $this->realisedTrainings = new ArrayCollection();
    }

    /**
     * @Groups({"default", "summary"})
     */
    public function getIntensity(): float
    {
        $intensity = 0;
        foreach ($this->cycles as $cycle) {
            foreach ($cycle->getExercises() as $exercise) {
                $intensity += $exercise->getIntensity();
            }
        }
        return $intensity;
    }

    /**
     * @Groups({"default"})
     */
    public function getEstimatedTimeInSeconds(): int
    {
        $totalTime = 0;
        $totalCycleTime = 0;
        $nbCycles = count($this->cycles);

        if ($nbCycles == 0) {
            return 0;
        }
        foreach ($this->cycles as $cycle) {
            $oneCycleTime = 0;
            foreach ($cycle->getExercises() as $exercise) {
                // $exerciseReference = $exercise->getExerciseReference();
                $sets = $exercise->getSets();
                $totalExerciseTime = 0;
                foreach ($sets as $set) {
                    $totalSetTime = $set->getReps() * $exercise->getTotalTimeUnderTension();
                    $totalExerciseTime += ($totalSetTime + $exercise->getRestBetweenSet());
                }
                $oneCycleTime += $totalExerciseTime;
            }
            $totalCycleTime = $oneCycleTime * $cycle->getNumberOfLoops() + ($cycle->getNumberOfLoops() - 1) * $cycle->getRestBetweenLoop();

            $totalTime += $totalCycleTime;
        }
        $totalTime += ((count($this->cycles) - 1) * $this->restBetweenCycles);

        return $totalTime;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|ExerciseCycle[]
     */
    public function getCycles(): Collection
    {
        return $this->cycles;
    }

    public function addCycle(ExerciseCycle $cycle): self
    {
        if (!$this->cycles->contains($cycle)) {
            $this->cycles[] = $cycle;
            $cycle->setTraining($this);
        }

        return $this;
    }

    public function removeCycle(ExerciseCycle $cycle): self
    {
        if ($this->cycles->removeElement($cycle)) {
            // set the owning side to null (unless already changed)
            if ($cycle->getTraining() === $this) {
                $cycle->setTraining(null);
            }
        }

        return $this;
    }

    public function getRestBetweenCycles(): ?int
    {
        return $this->restBetweenCycles;
    }

    public function setRestBetweenCycles(int $restBetweenCycles): self
    {
        $this->restBetweenCycles = $restBetweenCycles;

        return $this;
    }

    /**
     * @return Collection|SavedTraining[]
     */
    public function getSavedTrainings(): Collection
    {
        return $this->savedTrainings;
    }

    public function addSavedTraining(SavedTraining $savedTraining): self
    {
        if (!$this->savedTrainings->contains($savedTraining)) {
            $this->savedTrainings[] = $savedTraining;
            $savedTraining->setTrainingReference($this);
        }

        return $this;
    }

    public function removeSavedTraining(SavedTraining $savedTraining): self
    {
        if ($this->savedTrainings->removeElement($savedTraining)) {
            // set the owning side to null (unless already changed)
            if ($savedTraining->getTrainingReference() === $this) {
                $savedTraining->setTrainingReference(null);
            }
        }

        return $this;
    }

    public function getIsOfficial(): ?bool
    {
        return $this->isOfficial;
    }

    public function setIsOfficial(bool $isOfficial): self
    {
        $this->isOfficial = $isOfficial;

        return $this;
    }

    public function getReference(): ?self
    {
        return $this->reference;
    }

    public function setReference(?self $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getRealisedTrainings(): Collection
    {
        return $this->realisedTrainings;
    }

    public function addRealisedTraining(self $realisedTraining): self
    {
        if (!$this->realisedTrainings->contains($realisedTraining)) {
            $this->realisedTrainings[] = $realisedTraining;
            $realisedTraining->setReference($this);
        }

        return $this;
    }

    public function removeRealisedTraining(self $realisedTraining): self
    {
        if ($this->realisedTrainings->removeElement($realisedTraining)) {
            // set the owning side to null (unless already changed)
            if ($realisedTraining->getReference() === $this) {
                $realisedTraining->setReference(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt ?? new DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreFlush
     */
    public function onPrePersistSetRegistrationDate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getHasBeenRealisedOnceByUser(): ?bool
    {
        return $this->hasBeenRealisedOnceByUser;
    }

    public function setHasBeenRealisedOnceByUser(bool $hasBeenRealisedOnceByUser): self
    {
        $this->hasBeenRealisedOnceByUser = $hasBeenRealisedOnceByUser;

        return $this;
    }
}
