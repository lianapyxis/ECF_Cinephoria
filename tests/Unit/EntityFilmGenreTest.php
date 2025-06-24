<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Seance;
use App\Entity\Film;
use App\Entity\FilmGenre;
use App\Entity\Rating;

class EntityFilmGenreTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $filmGenre = new FilmGenre();
        $filmGenre->setName('Genre film')
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($filmGenre);

        $this->assertCount(0, $errors);

    }

}
