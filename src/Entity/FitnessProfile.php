<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FitnessProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FitnessProfileRepository::class)
 */
#[ApiResource(
    itemOperations: [
        "get" => [
            'denormalization_context' => ['groups' => ['default']],
        ],
        "patch" => [
            'denormalization_context' => ['groups' => ['default']],
        ]
    ],
    normalizationContext: ['groups' => ['default']],
    denormalizationContext: ['groups' => ['default']],
)]
class FitnessProfile
{
    const GOAL_STRENTGH = "STRENGTH";
    const GOAL_ENDURANCE = "ENDURANCE";
    const GOAL_HYPERTROPHY = "HYPERTROPHY";
    const GOAL_EXPLOSIVITY = "EXPLOSIVITY";

    /**
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $experience;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="array")
     */
    private $goals = [];

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="fitnessProfile", cascade={"persist", "remove"})
     */
    private $user;


    public function __construct()
    {
        $this->experience = 0;
        $this->age = 25;
        $this->weight = 80;
        $this->goals = [self::GOAL_HYPERTROPHY];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getGoals(): ?array
    {
        return $this->goals;
    }

    public function setGoals(array $goals): self
    {
        $this->goals = $goals;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setFitnessProfile(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getFitnessProfile() !== $this) {
            $user->setFitnessProfile($this);
        }

        $this->user = $user;

        return $this;
    }
}
