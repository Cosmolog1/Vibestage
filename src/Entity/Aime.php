<?php

namespace App\Entity;

use App\Repository\AimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AimeRepository::class)]
class Aime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'aime')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'aime')]
    private ?Event $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }
}
