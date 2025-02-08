<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Film>
     */
    #[ORM\OneToMany(targetEntity: Film::class, mappedBy: 'rating')]
    private Collection $id_film;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_add = null;

    public function __construct()
    {
        $this->id_film = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Film>
     */
    public function getIdFilm(): Collection
    {
        return $this->id_film;
    }

    public function addIdFilm(Film $idFilm): static
    {
        if (!$this->id_film->contains($idFilm)) {
            $this->id_film->add($idFilm);
            $idFilm->setRating($this);
        }

        return $this;
    }

    public function removeIdFilm(Film $idFilm): static
    {
        if ($this->id_film->removeElement($idFilm)) {
            // set the owning side to null (unless already changed)
            if ($idFilm->getRating() === $this) {
                $idFilm->setRating(null);
            }
        }

        return $this;
    }

    public function getDateAdd(): ?\DateTimeImmutable
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeImmutable $date_add): static
    {
        $this->date_add = $date_add;

        return $this;
    }
}
