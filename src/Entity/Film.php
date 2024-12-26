<?php

namespace App\Entity;

use App\Film\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $year = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $imgPath = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?float $staff_favourite = 0;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_add = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'film')]
    private Collection $comments;

    /**
     * @var Collection<int, FilmNote>
     */
    #[ORM\OneToMany(targetEntity: FilmNote::class, mappedBy: 'film', orphanRemoval: true)]
    private Collection $notes;

    #[ORM\ManyToOne(targetEntity: FilmGenre::class)]
    private FilmGenre $genre;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function __toString(){
        return $this->getTitle();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(string $imgPath): static
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function getGenre(): ?FilmGenre
    {
        return $this->genre;
    }

    public function setGenre(?FilmGenre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getStaffFavourite(): ?bool
    {
        return $this->staff_favourite;
    }

    public function setStaffFavourite(?bool $staff_favourite): static
    {
        $this->staff_favourite = $staff_favourite;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeImmutable
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeImmutable $date_add): static
    {
        $this->date_add = $date_add;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setFilm($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getFilm() === $this) {
                $comment->setFilm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FilmNote>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNotes(FilmNote $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setFilm($this);
        }

        return $this;
    }

    public function removeNote(FilmNote $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getFilm() === $this) {
                $note->setFilm(null);
            }
        }

        return $this;
    }

/*    public function removeGenre(?FilmGenre $genre): static
    {
        if ($this->genres->removeElement($genre)) {
            // set the owning side to null (unless already changed)
            if ($genre->getGenre() === $this) {
                $genre->setGenre(null);
            }
        }

        return $this;
    }*/

}
