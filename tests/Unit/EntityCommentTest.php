<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;
use App\Entity\Film;
use App\Entity\Comment;
use App\Entity\Rating;

class EntityCommentTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $film = $em->find('App\Entity\Film',1);
        $user = $em->find('App\Entity\User',4);

        $comment = new Comment();
        $comment->setComment('commentaire')
            ->setFilm($film)
            ->setUser($user)
            ->setStatus('DRAFT')
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($comment);

        $this->assertCount(0, $errors);

    }

}
