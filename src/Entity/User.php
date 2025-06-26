<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $birthday = null;

    /**
     * @var Collection<int, Abonnees>
     */
    #[ORM\OneToMany(targetEntity: Abonnees::class, mappedBy: 'user')]
    private Collection $abonnees;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user')]
    private Collection $comment;

    /**
     * @var Collection<int, Aime>
     */
    #[ORM\OneToMany(targetEntity: Aime::class, mappedBy: 'user')]
    private Collection $aime;

    public function __construct()
    {
        $this->abonnees = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->aime = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTime $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection<int, Abonnees>
     */
    public function getAbonnees(): Collection
    {
        return $this->abonnees;
    }

    public function addAbonnee(Abonnees $abonnee): static
    {
        if (!$this->abonnees->contains($abonnee)) {
            $this->abonnees->add($abonnee);
            $abonnee->setUser($this);
        }

        return $this;
    }

    public function removeAbonnee(Abonnees $abonnee): static
    {
        if ($this->abonnees->removeElement($abonnee)) {
            // set the owning side to null (unless already changed)
            if ($abonnee->getUser() === $this) {
                $abonnee->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Aime>
     */
    public function getAime(): Collection
    {
        return $this->aime;
    }

    public function addAime(Aime $aime): static
    {
        if (!$this->aime->contains($aime)) {
            $this->aime->add($aime);
            $aime->setUser($this);
        }

        return $this;
    }

    public function removeAime(Aime $aime): static
    {
        if ($this->aime->removeElement($aime)) {
            // set the owning side to null (unless already changed)
            if ($aime->getUser() === $this) {
                $aime->setUser(null);
            }
        }

        return $this;
    }
}
