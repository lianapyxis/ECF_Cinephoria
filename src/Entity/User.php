<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    #[ORM\Column]
    private string $firstname;
    #[ORM\Column]
    private string $lastname;
    #[ORM\Column,]
    private string $username;
    #[ORM\Column,]
    private string $email;
    #[ORM\Column]
    private string $password;
    #[ORM\Column(type: 'json')]
    private array $roles = [];
    #[ORM\Column]
    private ?\DateTimeImmutable $date_add = null;

    /**
     * @var Collection<int, FilmNote>
     */
    #[ORM\OneToMany(targetEntity: FilmNote::class, mappedBy: 'User', orphanRemoval: true)]
    private Collection $filmNotes;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'id_user')]
    private Collection $reservations;

    public function __construct()
    {
        $this->filmNotes = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

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
    public function eraseCredentials(): void
    {
        return ;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getdateAdd(): ?\DateTimeImmutable
    {
        return $this->date_add;
    }

    public function setdateAdd(\DateTimeImmutable $date_add): static
    {
        $this->date_add = $date_add;

        return $this;
    }

    /**
     * @return Collection<int, FilmNote>
     */
    public function getFilmNotes(): Collection
    {
        return $this->filmNotes;
    }

    public function addFilmNote(FilmNote $filmNote): static
    {
        if (!$this->filmNotes->contains($filmNote)) {
            $this->filmNotes->add($filmNote);
            $filmNote->setUser($this);
        }

        return $this;
    }

    public function removeFilmNote(FilmNote $filmNote): static
    {
        if ($this->filmNotes->removeElement($filmNote)) {
            // set the owning side to null (unless already changed)
            if ($filmNote->getUser() === $this) {
                $filmNote->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setIdUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdUser() === $this) {
                $reservation->setIdUser(null);
            }
        }

        return $this;
    }

}