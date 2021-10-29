<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ApiSubresource()
     * @ORM\OneToMany(targetEntity=Training::class, mappedBy="author")
     */
    private $trainings;

    /**
     * @ORM\OneToMany(targetEntity=SavedTraining::class, mappedBy="user", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $savedTrainings;

    /**
     * @Groups({"user_team"})
     * @ORM\ManyToOne(targetEntity=FitnessTeam::class, inversedBy="members")
     */
    private $fitnessTeam;

    /**
     * @Groups({"default"})
     * @ORM\OneToOne(targetEntity=FitnessProfile::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $fitnessProfile;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=ApiToken::class, mappedBy="associatedUser", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $apiTokens;

    /**
     * @Groups({"default"})
     * @ORM\Column(type="datetime", options={"default" : "CURRENT_TIMESTAMP"})     
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     * @Groups({"default"})
     */
    private $isMale = true;

    public function __construct()
    {
        $this->trainings = new ArrayCollection();
        $this->savedTrainings = new ArrayCollection();
        $this->apiTokens = new ArrayCollection();
        $this->isMale = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Training[]
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): self
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings[] = $training;
            $training->setAuthor($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): self
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getAuthor() === $this) {
                $training->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SavedTraining[]
     */
    public function getSavedTrainings(): Collection
    {
        return $this->savedTrainings;
    }

    public function addSavedTraining(SavedTraining $savedTraining): self
    {
        if (!$this->savedTrainings->contains($savedTraining)) {
            $this->savedTrainings[] = $savedTraining;
            $savedTraining->setUser($this);
        }

        return $this;
    }

    public function removeSavedTraining(SavedTraining $savedTraining): self
    {
        if ($this->savedTrainings->removeElement($savedTraining)) {
            // set the owning side to null (unless already changed)
            if ($savedTraining->getUser() === $this) {
                $savedTraining->setUser(null);
            }
        }

        return $this;
    }

    public function getFitnessTeam(): ?FitnessTeam
    {
        return $this->fitnessTeam;
    }

    public function setFitnessTeam(?FitnessTeam $fitnessTeam): self
    {
        $this->fitnessTeam = $fitnessTeam;

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

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setAssociatedUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getAssociatedUser() === $this) {
                $apiToken->setAssociatedUser(null);
            }
        }

        return $this;
    }



    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function createDefaultProfile()
    {
        $this->createdAt = new \DateTime();
        $this->fitnessProfile = new FitnessProfile();
    }

    public function getIsMale(): ?bool
    {
        return $this->isMale;
    }

    public function setIsMale(bool $isMale): self
    {
        $this->isMale = $isMale;

        return $this;
    }
}
