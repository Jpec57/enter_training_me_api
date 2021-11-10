<?php

namespace App\Entity;

use App\Repository\UserReactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserReactionRepository::class)
 */
class UserReaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"minimal"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userReactions")
     */
    private $user;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="integer", options={"default" : 0}))
     */
    private $reactionType;

    /**
     * @ORM\ManyToOne(targetEntity=Training::class, inversedBy="userReactions")
     */
    private $training;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReactionType(): ?int
    {
        return $this->reactionType;
    }

    public function setReactionType(int $reactionType): self
    {
        $this->reactionType = $reactionType;

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
