<?php

namespace App\Entity;

use App\Repository\UserRelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=UserRelationRepository::class)
 */
#[ApiResource(
    //Use to read / GET
    normalizationContext: ['groups' => ['relation']],
    //USE TO WRITE
    denormalizationContext: ['groups' => ['relation']],
)]
class UserRelation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"relation"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="givenUserRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fromUser;

    /**
     * @Groups({"relation"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="becomingUserRelations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $toUser;

    /**
     * @Groups({"relation"})
     * @ORM\Column(type="integer")
     */
    private $visibilityLevel;

    /**
     * @Groups({"relation"})
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isFollowing;

    public function __construct()
    {
        $this->isFollowing = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromUser(): ?User
    {
        return $this->fromUser;
    }

    public function setFromUser(?User $fromUser): self
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    public function getToUser(): ?User
    {
        return $this->toUser;
    }

    public function setToUser(?User $toUser): self
    {
        $this->toUser = $toUser;

        return $this;
    }

    public function getVisibilityLevel(): ?int
    {
        return $this->visibilityLevel;
    }

    public function setVisibilityLevel(int $visibilityLevel): self
    {
        $this->visibilityLevel = $visibilityLevel;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsFollowing(): ?bool
    {
        return $this->isFollowing;
    }

    public function setIsFollowing(bool $isFollowing): self
    {
        $this->isFollowing = $isFollowing;

        return $this;
    }
}
