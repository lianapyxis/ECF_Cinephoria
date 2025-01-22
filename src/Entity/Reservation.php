<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seance $id_seance = null;

    #[ORM\Column]
    private ?float $cost_total = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_add = null;

    /**
     * @var Collection<int, ReservationDetails>
     */
    #[ORM\OneToMany(targetEntity: ReservationDetails::class, mappedBy: 'id_reservation', cascade: ['persist', 'remove'])]
    private Collection $reservationDetails;

    public function __construct()
    {
        $this->reservationDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdSeance(): ?Seance
    {
        return $this->id_seance;
    }

    public function setIdSeance(?Seance $id_seance): static
    {
        $this->id_seance = $id_seance;

        return $this;
    }

    public function getCostTotal(): ?float
    {
        return $this->cost_total;
    }

    public function setCostTotal(float $cost_total): static
    {
        $this->cost_total = $cost_total;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

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

    /**
     * @return Collection<int, ReservationDetails>
     */
    public function getReservationDetails(): Collection
    {
        return $this->reservationDetails;
    }

    public function addReservationDetail(ReservationDetails $reservationDetail): static
    {
        if (!$this->reservationDetails->contains($reservationDetail)) {
            $this->reservationDetails->add($reservationDetail);
            $reservationDetail->setIdReservation($this);
        }

        return $this;
    }

    public function removeReservationDetail(ReservationDetails $reservationDetail): static
    {
        if ($this->reservationDetails->removeElement($reservationDetail)) {
            // set the owning side to null (unless already changed)
            if ($reservationDetail->getIdReservation() === $this) {
                $reservationDetail->setIdReservation(null);
            }
        }

        return $this;
    }
}
