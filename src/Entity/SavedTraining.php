<?php

namespace App\Entity;

use App\Repository\SavedTrainingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=SavedTrainingRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']],
    denormalizationContext: ['groups' => ['default', 'realised_exercise_set', 'realised_exercise_exercise_reference', 'exercise_cycle_exercise', 'training_exercise_cycle', 'training_user', 'exercise_cycle_exercise']],
)]
class SavedTraining
{
    /**
     * @ORM\Id
     * @Groups({"default"})
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"default"})
     * @ORM\ManyToOne(targetEntity=Training::class, inversedBy="savedTrainings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trainingReference;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="savedTrainings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrainingReference(): ?Training
    {
        return $this->trainingReference;
    }

    public function setTrainingReference(?Training $trainingReference): self
    {
        $this->trainingReference = $trainingReference;

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
}
