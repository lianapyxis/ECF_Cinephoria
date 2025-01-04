<?php

namespace App\Entity;

use App\Repository\DamagedPlaceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: DamagedPlaceRepository::class)]
#[Broadcast]
class DamagedPlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'damagedPlaces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $id_room = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_add = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRoom(): ?Room
    {
        return $this->id_room;
    }

    public function setIdRoom(?Room $id_room): static
    {
        $this->id_room = $id_room;

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
}
