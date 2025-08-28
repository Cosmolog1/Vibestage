<?php

namespace App\Entity;

use App\Repository\ArtisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtisteRepository::class)]
class Artiste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column]
    private ?string $nbAbonne = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Abonnees>
     */
    #[ORM\OneToMany(targetEntity: Abonnees::class, mappedBy: 'artiste')]
    private Collection $abonnees;

    #[ORM\ManyToOne(inversedBy: 'artistes')]
    private ?Musique $musique = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $media = null;




    public function __construct()
    {
        $this->abonnees = new ArrayCollection();
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

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getNbAbonne(): ?string
    {
        return $this->nbAbonne;
    }

    public function setNbAbonne(string $nbAbonne): static
    {
        $this->nbAbonne = $nbAbonne;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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
            $abonnee->setArtiste($this);
        }

        return $this;
    }

    public function removeAbonnee(Abonnees $abonnee): static
    {
        if ($this->abonnees->removeElement($abonnee)) {
            // set the owning side to null (unless already changed)
            if ($abonnee->getArtiste() === $this) {
                $abonnee->setArtiste(null);
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
}
