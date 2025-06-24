<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Seance;
use App\Entity\User;
use App\Entity\Reservation;

class EntityReservationTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $seance = $em->find('App\Entity\Seance',1);
        $user = $em->find('App\Entity\User',4);

        $reservation = new Reservation();
        $reservation->setIdUser($user)
            ->setIdSeance($seance)
            ->setCostTotal(12.00)
            ->setStatus(0)
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($reservation);

        $this->assertCount(0, $errors);

    }

}
