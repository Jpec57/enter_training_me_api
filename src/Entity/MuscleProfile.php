<?php

namespace App\Entity;

use App\Repository\MuscleProfileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MuscleProfileRepository::class)
 */
class MuscleProfile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $backExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $chestExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $tricepsExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $bicepsExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $absExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $quadricepsExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $hamstringExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $calfExperience;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $forearmExperience;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getChestExperience(): ?int
    {
        return $this->chestExperience;
    }

    public function setChestExperience(int $chestExperience): self
    {
        $this->chestExperience = $chestExperience;

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

    public function getBicepsExperience(): ?int
    {
        return $this->bicepsExperience;
    }

    public function setBicepsExperience(int $bicepsExperience): self
    {
        $this->bicepsExperience = $bicepsExperience;

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

    public function getQuadricepsExperience(): ?int
    {
        return $this->quadricepsExperience;
    }

    public function setQuadricepsExperience(int $quadricepsExperience): self
    {
        $this->quadricepsExperience = $quadricepsExperience;

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

    public function getCalfExperience(): ?int
    {
        return $this->calfExperience;
    }

    public function setCalfExperience(int $calfExperience): self
    {
        $this->calfExperience = $calfExperience;

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
}
