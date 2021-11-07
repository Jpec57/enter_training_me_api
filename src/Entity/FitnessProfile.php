<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FitnessProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @Groups({"default"})
     * @ORM\OneToMany(targetEntity=FitnessBadge::class, mappedBy="fitnessProfile", orphanRemoval=true)
     */
    private $badges;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $hamstringExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $quadricepsExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $calfExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $absExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $forearmExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $bicepsExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $tricepsExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $shoulderExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $chestExperience = 0;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $backExperience = 0;


    public function __construct()
    {
        $this->experience = 0;
        $this->age = 25;
        $this->weight = 80;
        $this->goals = [self::GOAL_HYPERTROPHY];
        $this->badges = new ArrayCollection();
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

    /**
     * @return Collection|FitnessBadge[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(FitnessBadge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
            $badge->setFitnessProfile($this);
        }

        return $this;
    }

    public function removeBadge(FitnessBadge $badge): self
    {
        if ($this->badges->removeElement($badge)) {
            // set the owning side to null (unless already changed)
            if ($badge->getFitnessProfile() === $this) {
                $badge->setFitnessProfile(null);
            }
        }

        return $this;
    }

    public function getHamstringExperience(): ?int
    {
        return $this->hamstringExperience;
    }

    public function setHamstringExperience(int $hamstringExperience): self
    {
        $this->hamstringExperience = $hamstringExperience;

        return $this;
    }

    public function getQuadricepsExperience(): ?int
    {
        return $this->quadricepsExperience;
    }

    public function setQuadricepsExperience(int $quadricepsExperience): self
    {
        $this->quadricepsExperience = $quadricepsExperience;

        return $this;
    }

    public function getCalfExperience(): ?int
    {
        return $this->calfExperience;
    }

    public function setCalfExperience(int $calfExperience): self
    {
        $this->calfExperience = $calfExperience;

        return $this;
    }

    public function getAbsExperience(): ?int
    {
        return $this->absExperience;
    }

    public function setAbsExperience(int $absExperience): self
    {
        $this->absExperience = $absExperience;

        return $this;
    }

    public function getForearmExperience(): ?int
    {
        return $this->forearmExperience;
    }

    public function setForearmExperience(int $forearmExperience): self
    {
        $this->forearmExperience = $forearmExperience;

        return $this;
    }

    public function getBicepsExperience(): ?int
    {
        return $this->bicepsExperience;
    }

    public function setBicepsExperience(int $bicepsExperience): self
    {
        $this->bicepsExperience = $bicepsExperience;

        return $this;
    }

    public function getTricepsExperience(): ?int
    {
        return $this->tricepsExperience;
    }

    public function setTricepsExperience(int $tricepsExperience): self
    {
        $this->tricepsExperience = $tricepsExperience;

        return $this;
    }

    public function getShoulderExperience(): ?int
    {
        return $this->shoulderExperience;
    }

    public function setShoulderExperience(int $shoulderExperience): self
    {
        $this->shoulderExperience = $shoulderExperience;

        return $this;
    }

    public function getChestExperience(): ?int
    {
        return $this->chestExperience;
    }

    public function setChestExperience(int $chestExperience): self
    {
        $this->chestExperience = $chestExperience;

        return $this;
    }

    public function getBackExperience(): ?int
    {
        return $this->backExperience;
    }

    public function setBackExperience(int $backExperience): self
    {
        $this->backExperience = $backExperience;

        return $this;
    }
}
