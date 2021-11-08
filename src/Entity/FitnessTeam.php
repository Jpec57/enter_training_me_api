<?php

namespace App\Entity;

use App\Repository\FitnessTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=FitnessTeamRepository::class)
 */
#[ApiResource(
    order: ["name" => "ASC"],
    //Use to read / GET
    normalizationContext: ['groups' => ['default', 'team']],
    //USE TO WRITE
    denormalizationContext: ['groups' => ['default', 'team']],
)]
class FitnessTeam
{
    /**
     * @Groups({"default"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"team"})
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="fitnessTeam")
     */
    private $members;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $experience;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $banner;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setFitnessTeam($this);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getFitnessTeam() === $this) {
                $member->setFitnessTeam(null);
            }
        }

        return $this;
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }
}
