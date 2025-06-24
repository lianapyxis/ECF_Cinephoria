<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;
use App\Entity\Film;
use App\Entity\FilmNote;
use App\Entity\Rating;

class EntityFilmNoteTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $film = $em->find('App\Entity\Film',1);
        $user = $em->find('App\Entity\User',4);

        $filmNote = new FilmNote();
        $filmNote->setNote(5)
            ->setFilm($film)
            ->setUser($user)
        ;

        $errors = $container->get('validator')->validate($filmNote);

        $this->assertCount(0, $errors);

    }

}
