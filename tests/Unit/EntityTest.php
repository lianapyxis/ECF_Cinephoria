<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Seance;
use App\Entity\Film;
use App\Entity\Room;
use App\Entity\Rating;

class EntityTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $film = $em->find('App\Entity\Film',1);
        $room = $em->find('App\Entity\Room',1);

        $seance = new Seance();
        $seance->setIdFilm($film)
            ->setIdRoom($room)
            ->setTimeStart(new \DateTimeImmutable())
            ->setTimeEnd(new \DateTimeImmutable())
            ->setPriceTtc(8)
            ->setDateAdd(new \DateTimeImmutable())
            ;

        $errors = $container->get('validator')->validate($seance);

        $this->assertCount(0, $errors);

    }

    public function testIsInputInvalid(){

        self::bootKernel();

        $container = static::getContainer();

        $rating = new Rating();
        $rating->setTitle('')
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($rating);

        $this->assertCount(1, $errors);
    }

}
