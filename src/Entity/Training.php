<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TrainingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrainingRepository::class)
 */
#[ApiResource]
class Training
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="trainings")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=ExerciceCycle::class, mappedBy="training")
     */
    private $cycles;

    /**
     * @ORM\Column(type="integer")
     */
    private $restBetweenCycles;

    /**
     * @ORM\OneToMany(targetEntity=SavedTraining::class, mappedBy="trainingReference")
     */
    private $savedTrainings;

    public function __construct()
    {
        $this->cycles = new ArrayCollection();
        $this->savedTrainings = new ArrayCollection();
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
     * @return Collection|ExerciceCycle[]
     */
    public function getCycles(): Collection
    {
        return $this->cycles;
    }

    public function addCycle(ExerciceCycle $cycle): self
    {
        if (!$this->cycles->contains($cycle)) {
            $this->cycles[] = $cycle;
            $cycle->setTraining($this);
        }

        return $this;
    }

    public function removeCycle(ExerciceCycle $cycle): self
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
}
