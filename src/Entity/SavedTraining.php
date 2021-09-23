<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SavedTrainingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SavedTrainingRepository::class)
 */
#[ApiResource]
class SavedTraining
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
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
