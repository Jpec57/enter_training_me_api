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
    order: ["createdAt" => "DESC"],
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
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $numberOfLoops = 1;

    /**
     * @Groups({"default", "summary"})
     * @ORM\Column(type="integer")
     */
    private $restBetweenCycles = 60;

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

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isPremium;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="restrictedAccessTrainings")
     */
    private $restrictedAuthorizedUserList;

    /**
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity=RealisedExercise::class, mappedBy="training", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $exercises;

    public function __construct()
    {
        $this->savedTrainings = new ArrayCollection();
        $this->isOfficial = false;
        $this->isPremium = false;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->hasBeenRealisedOnceByUser = true;
        $this->realisedTrainings = new ArrayCollection();
        $this->restrictedAuthorizedUserList = new ArrayCollection();
        $this->exercises = new ArrayCollection();
    }

    /**
     * @Groups({"default", "summary"})
     */
    public function getIntensity(): float
    {
        $intensity = 0;
        foreach ($this->getExercises() as $exercise) {
            $intensity += $exercise->getIntensity();
        }
        return $intensity;
    }

    /**
     * @Groups({"default", "summary"})
     */
    public function getDifficulty(): string
    {
        $intensity = $this->getIntensity();
        if (9000 <= $intensity) {
            return "ELITE";
        } else if (7000 <= $intensity && $intensity < 9000) {
            return "EXPERT";
        } else if (5000 <= $intensity && $intensity < 7000) {
            return "ADVANCED";
        } else if (3000 <= $intensity && $intensity < 5000) {
            return "INTERMEDIATE";
        } else if (1000 <= $intensity && $intensity < 3000) {
            return "NOVICE";
        }
        return "BEGINNER";
    }

    /**
     * @Groups({"default"})
     */
    public function getEstimatedTimeInSeconds(): int
    {
        $totalTime = 0;
        $totalCycleTime = 0;
        $nbCycles = $this->numberOfLoops;

        if (empty($this->exercises)) {
            return 0;
        }
        $oneCycleTime = 0;
        foreach ($this->getExercises() as $exercise) {
            // $exerciseReference = $exercise->getExerciseReference();
            $sets = $exercise->getSets();
            $totalExerciseTime = 0;
            foreach ($sets as $set) {
                $totalSetTime = $set->getReps() * $exercise->getTotalTimeUnderTension();
                $totalExerciseTime += ($totalSetTime + $exercise->getRestBetweenSet());
            }
            $oneCycleTime += $totalExerciseTime;
        }
        $totalTime = $nbCycles * $oneCycleTime + ($nbCycles - 1) * $this->restBetweenCycles;

        return $totalTime;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setNumberOfLoops(?int $numberOfLoops): self
    {
        $this->numberOfLoops = $numberOfLoops;
        return $this;
    }


    public function getNumberOfLoops(): ?int
    {
        return $this->numberOfLoops;
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

    public function getIsPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(bool $isPremium): self
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRestrictedAuthorizedUserList(): Collection
    {
        return $this->restrictedAuthorizedUserList;
    }

    public function addRestrictedAuthorizedUserList(User $restrictedAuthorizedUserList): self
    {
        if (!$this->restrictedAuthorizedUserList->contains($restrictedAuthorizedUserList)) {
            $this->restrictedAuthorizedUserList[] = $restrictedAuthorizedUserList;
        }

        return $this;
    }

    public function removeRestrictedAuthorizedUserList(User $restrictedAuthorizedUserList): self
    {
        $this->restrictedAuthorizedUserList->removeElement($restrictedAuthorizedUserList);

        return $this;
    }

    /**
     * @return Collection|RealisedExercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(RealisedExercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->setTraining($this);
        }

        return $this;
    }

    public function removeExercise(RealisedExercise $exercise): self
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getTraining() === $this) {
                $exercise->setTraining(null);
            }
        }

        return $this;
    }
}
