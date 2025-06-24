<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Room;
use App\Entity\SpecialPlace;

class EntitySpecialPlaceTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $room = $em->find('App\Entity\Room',1);

        $specialPlace = new SpecialPlace();
        $specialPlace->setIdRoom($room)
            ->setPlace('A1')
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($specialPlace);

        $this->assertCount(0, $errors);

    }
}
