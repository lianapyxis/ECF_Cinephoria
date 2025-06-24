<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Format;
use App\Entity\TypeSeats;
use App\Entity\Room;
use App\Entity\City;
use App\Entity\Rating;

class EntityRoomTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $city = $em->find('App\Entity\City',1);
        $format = $em->find('App\Entity\Format',1);
        $typeSeats = $em->find('App\Entity\TypeSeats',1);

        $room = new Room();
        $room->setTitle('title')
            ->setIdCity($city)
            ->setNumberSeats(50)
            ->setNumberRows(5)
            ->setFormat($format)
            ->setTypeSeats($typeSeats)
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($room);

        $this->assertCount(0, $errors);

    }

}
