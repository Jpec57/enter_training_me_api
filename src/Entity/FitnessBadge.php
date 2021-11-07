<?php

namespace App\Entity;

use App\Repository\FitnessBadgeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FitnessBadgeRepository::class)
 */
class FitnessBadge
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagePath;

    /**
     * @ORM\ManyToOne(targetEntity=FitnessProfile::class, inversedBy="badges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fitnessProfile;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getFitnessProfile(): ?FitnessProfile
    {
        return $this->fitnessProfile;
    }

    public function setFitnessProfile(?FitnessProfile $fitnessProfile): self
    {
        $this->fitnessProfile = $fitnessProfile;

        return $this;
    }
}
