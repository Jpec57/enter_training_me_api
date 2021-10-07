<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExecutionStyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ExecutionStyleRepository::class)
 */
#[ApiResource(
    itemOperations: ["get"],
    denormalizationContext: ['groups' => [
        'default',
    ]],
    normalizationContext: ['groups' => [
        'default',
    ]]
)]
#[UniqueEntity("name")]
class ExecutionStyle
{
    const DEFAULT_EXECUTION_TEMPO = "3/0/1/0";

    /**
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="float")
     */
    private $strainFactor;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ExerciseFormat::class, mappedBy="executionStyle")
     */
    private $exerciseFormats;


    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $timeUnderTension;

    //https://fr.myprotein.com/thezone/entrainement/tempos-musculation/



    public function __construct()
    {
        $this->exerciseFormats = new ArrayCollection();
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

    public function getStrainFactor(): ?float
    {
        return $this->strainFactor;
    }

    public function setStrainFactor(float $strainFactor): self
    {
        $this->strainFactor = $strainFactor;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ExerciseFormat[]
     */
    public function getExerciseFormats(): Collection
    {
        return $this->exerciseFormats;
    }

    public function addExerciseFormat(ExerciseFormat $exerciseFormat): self
    {
        if (!$this->exerciseFormats->contains($exerciseFormat)) {
            $this->exerciseFormats[] = $exerciseFormat;
            $exerciseFormat->setExecutionStyle($this);
        }

        return $this;
    }

    public function removeExerciseFormat(ExerciseFormat $exerciseFormat): self
    {
        if ($this->exerciseFormats->removeElement($exerciseFormat)) {
            // set the owning side to null (unless already changed)
            if ($exerciseFormat->getExecutionStyle() === $this) {
                $exerciseFormat->setExecutionStyle(null);
            }
        }

        return $this;
    }

    //strength 5/0/1/0
    //hypertrophy 3/0/1/0
    //endurance 4/2/1/1
    public function getTimeUnderTension(): string
    {
        return $this->timeUnderTension ?? self::DEFAULT_EXECUTION_TEMPO;
    }

    public function setTimeUnderTension(?string $timeUnderTension): self
    {
        $this->timeUnderTension = $timeUnderTension;

        return $this;
    }

    public function getTimeUnderTensionAsArray(): array
    {
        return explode("/", $this->getTimeUnderTension());
    }

    public function getTotalTimeUnderTension(): int
    {
        $total = 0;
        foreach ($this->getTimeUnderTensionAsArray() as $val) {
            $total += $val;
        }

        return $total;
    }
}
