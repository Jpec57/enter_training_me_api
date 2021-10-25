<?php

namespace App\Entity;

use App\Repository\SavedTrainingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=SavedTrainingRepository::class)
 */
#[ApiResource(
    attributes: ["security" =>  "is_granted('ROLE_ADMIN') or object.user == user"],
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

    /**
     * @Groups({"default"})
     * @ORM\Column(type="datetime", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $createdDate;

    public function __construct()
    {
        $this->createdDate = new DateTime();
    }

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

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }
}
