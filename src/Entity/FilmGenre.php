<?php

namespace App\Entity;

use App\Film\Repository\FilmGenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmGenreRepository::class)]
class FilmGenre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_add = null;

    #[ORM\OneToMany(targetEntity: Film::class, mappedBy: 'genre')]
    private Collection $films;

    public function __construct()
    {
        $this->films = new ArrayCollection();
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

    public function geDateAdd(): ?\DateTimeImmutable
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeImmutable $date_add): static
    {
        $this->date_add = $date_add;

        return $this;
    }

    /**
     * @return Collection<int, Film>
     */
    public function getFilm(): Collection
    {
        return $this->films;
    }

    public function addFilm(Film $film): static
    {
        if (!$this->films->contains($film)) {
            $this->films->add($film);
            $film->setGenre($this);
        }

        return $this;
    }

}
