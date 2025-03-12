<?php

declare(strict_types=1);

namespace App\Document;

use App\Entity\Room;
use App\Repository\DamagedPlaceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


#[MongoDB\Document(collection:'damaged_places')]
class DamagedPlace
{

    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(name: 'id_room',type: 'integer')]
    private ?int $id_room = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $damaged_places = null;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdRoom(): ?int
    {
        return $this->id_room;
    }

    public function getDamagedPlaces(): ?string
    {
        return $this->damaged_places;
    }

    public function setDamagedPlaces(string $damaged_places): static
    {
        $this->damaged_places = $damaged_places;

        return $this;
    }

}
