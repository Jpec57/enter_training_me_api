<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExerciseFormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ExerciseFormatRepository::class)
 */
#[ApiResource(

    itemOperations: ["get"],
    denormalizationContext: ['groups' => [
        'default', 'exercise_format_set'
    ]],
    normalizationContext: ['groups' => [
        'default', 'exercise_format_set'
    ]]
)]
class ExerciseFormat
{
    /**
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"exercise_format_set"})
     * @ORM\OneToMany(targetEntity=Set::class, mappedBy="exerciseFormat", cascade={"persist"})
     */
    private $predefinedSets;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer")
     */
    private $predefinedRest;

    /**
     * @ORM\ManyToOne(targetEntity=ExecutionStyle::class, inversedBy="exerciseFormats")
     */
    private $executionStyle;

    public function __construct()
    {
        $this->predefinedSets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Set[]
     */
    public function getPredefinedSets(): Collection
    {
        return $this->predefinedSets;
    }

    public function addPredefinedSet(Set $predefinedSet): self
    {
        if (!$this->predefinedSets->contains($predefinedSet)) {
            $this->predefinedSets[] = $predefinedSet;
            $predefinedSet->setExerciseFormat($this);
        }

        return $this;
    }

    public function removePredefinedSet(Set $predefinedSet): self
    {
        if ($this->predefinedSets->removeElement($predefinedSet)) {
            // set the owning side to null (unless already changed)
            if ($predefinedSet->getExerciseFormat() === $this) {
                $predefinedSet->setExerciseFormat(null);
            }
        }

        return $this;
    }

    public function getPredefinedRest(): ?int
    {
        return $this->predefinedRest;
    }

    public function setPredefinedRest(int $predefinedRest): self
    {
        $this->predefinedRest = $predefinedRest;

        return $this;
    }

    public function getExecutionStyle(): ?ExecutionStyle
    {
        return $this->executionStyle;
    }

    public function setExecutionStyle(?ExecutionStyle $executionStyle): self
    {
        $this->executionStyle = $executionStyle;

        return $this;
    }
}
