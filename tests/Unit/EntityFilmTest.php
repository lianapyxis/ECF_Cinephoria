<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Film;
use App\Entity\Rating;

class EntityFilmTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $rating = $em->find('App\Entity\Rating',1);

        $film = new Film();
        $film->setImgPath('img.jpg')
            ->setStaffFavourite(1)
            ->setRating($rating)
            ->setYear('2025')
            ->setTitle('Title')
            ->setDescription('description')
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($film);

        $this->assertCount(0, $errors);

    }

}
