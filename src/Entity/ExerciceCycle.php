<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExerciceCycleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExerciceCycleRepository::class)
 */
#[ApiResource]
class ExerciceCycle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=RealisedExercise::class, mappedBy="exerciceCycle", cascade={"persist"})
     */
    private $exercises;

    /**
     * @ORM\Column(type="integer")
     */
    private $restBetweenLoop = 60;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfLoops = 1;

    /**
     * @ORM\ManyToOne(targetEntity=Training::class, inversedBy="cycles")
     */
    private $training;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->numberOfLoops = 1;
        $this->restBetweenLoop = 60;
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $exercise->setExerciceCycle($this);
        }

        return $this;
    }

    public function removeExercise(RealisedExercise $exercise): self
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getExerciceCycle() === $this) {
                $exercise->setExerciceCycle(null);
            }
        }

        return $this;
    }

    public function getRestBetweenLoop(): ?int
    {
        return $this->restBetweenLoop;
    }

    public function setRestBetweenLoop(int $restBetweenLoop): self
    {
        $this->restBetweenLoop = $restBetweenLoop;

        return $this;
    }

    public function getNumberOfLoops(): ?int
    {
        return $this->numberOfLoops;
    }

    public function setNumberOfLoops(int $numberOfLoops): self
    {
        $this->numberOfLoops = $numberOfLoops;

        return $this;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): self
    {
        $this->training = $training;

        return $this;
    }
}
