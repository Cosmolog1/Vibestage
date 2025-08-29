<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $prog = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;


    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'event')]
    private Collection $comment;

    /**
     * @var Collection<int, Aime>
     */
    #[ORM\OneToMany(targetEntity: Aime::class, mappedBy: 'event')]
    private Collection $aime;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Musique $musique = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $media = null;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\ManyToMany(targetEntity: Location::class, inversedBy: 'events')]
    private Collection $location;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->aime = new ArrayCollection();
        $this->location = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getProg(): ?string
    {
        return $this->prog;
    }

    public function setProg(string $prog): static
    {
        $this->prog = $prog;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
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
            $aime->setEvent($this);
        }

        return $this;
    }

    public function removeAime(Aime $aime): static
    {
        if ($this->aime->removeElement($aime)) {
            // set the owning side to null (unless already changed)
            if ($aime->getEvent() === $this) {
                $aime->setEvent(null);
            }
        }

        return $this;
    }

    public function getMusique(): ?Musique
    {
        return $this->musique;
    }

    public function setMusique(?Musique $musique): static
    {
        $this->musique = $musique;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocation(): Collection
    {
        return $this->location;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->location->contains($location)) {
            $this->location->add($location);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        $this->location->removeElement($location);

        return $this;
    }
}
