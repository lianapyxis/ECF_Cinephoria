<?php

namespace App\Entity;

use App\Repository\ReservationDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationDetailsRepository::class)]
class ReservationDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservationDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $id_reservation = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_add = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdReservation(): ?Reservation
    {
        return $this->id_reservation;
    }

    public function setIdReservation(?Reservation $id_reservation): static
    {
        $this->id_reservation = $id_reservation;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

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
