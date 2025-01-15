<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $id_city = null;

    #[ORM\Column]
    private ?int $number_seats = null;

    #[ORM\Column]
    private ?int $number_rows = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_add = null;

    #[ORM\ManyToOne(inversedBy: 'room')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Format $format = null;

    #[ORM\ManyToOne(inversedBy: 'room')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeSeats $typeSeats = null;

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'id_room', cascade: ['persist', 'remove'])]
    private Collection $seances;

    /**
     * @var Collection<int, SpecialPlace>
     */
    #[ORM\OneToMany(targetEntity: SpecialPlace::class, mappedBy: 'id_room', cascade: ['persist', 'remove'])]
    public Collection $specialPlaces;

    /**
     * @var Collection<int, DamagedPlace>
     */
    #[ORM\OneToMany(targetEntity: DamagedPlace::class, mappedBy: 'id_room')]
    private Collection $damagedPlaces;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
        $this->specialPlaces = new ArrayCollection();
        $this->damagedPlaces = new ArrayCollection();
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

    public function getIdCity(): ?City
    {
        return $this->id_city;
    }

    public function setIdCity(?City $id_city): static
    {
        $this->id_city = $id_city;

        return $this;
    }

    public function getNumberSeats(): ?int
    {
        return $this->number_seats;
    }

    public function setNumberSeats(int $number_seats): static
    {
        $this->number_seats = $number_seats;

        return $this;
    }

    public function getNumberRows(): ?int
    {
        return $this->number_rows;
    }

    public function setNumberRows(int $number_rows): static
    {
        $this->number_rows = $number_rows;

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

    public function getFormat(): ?Format
    {
        return $this->format;
    }

    public function setFormat(?Format $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getTypeSeats(): ?TypeSeats
    {
        return $this->typeSeats;
    }

    public function setTypeSeats(?TypeSeats $typeSeats): static
    {
        $this->typeSeats = $typeSeats;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setIdRoom($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getIdRoom() === $this) {
                $seance->setIdRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SpecialPlace>
     */
    public function getSpecialPlaces(): Collection
    {
        return $this->specialPlaces;
    }

    public function addSpecialPlace(SpecialPlace $specialPlace): static
    {
        if (!$this->specialPlaces->contains($specialPlace)) {
            $this->specialPlaces->add($specialPlace);
            $specialPlace->setIdRoom($this);
        }

        return $this;
    }

    public function removeSpecialPlace(SpecialPlace $specialPlace): static
    {
        if ($this->specialPlaces->removeElement($specialPlace)) {
            // set the owning side to null (unless already changed)
            if ($specialPlace->getIdRoom() === $this) {
                $specialPlace->setIdRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DamagedPlace>
     */
    public function getDamagedPlaces(): Collection
    {
        return $this->damagedPlaces;
    }

    public function addDamagedPlace(DamagedPlace $damagedPlace): static
    {
        if (!$this->damagedPlaces->contains($damagedPlace)) {
            $this->damagedPlaces->add($damagedPlace);
            $damagedPlace->setIdRoom($this);
        }

        return $this;
    }

    public function removeDamagedPlace(DamagedPlace $damagedPlace): static
    {
        if ($this->damagedPlaces->removeElement($damagedPlace)) {
            // set the owning side to null (unless already changed)
            if ($damagedPlace->getIdRoom() === $this) {
                $damagedPlace->setIdRoom(null);
            }
        }

        return $this;
    }
}
