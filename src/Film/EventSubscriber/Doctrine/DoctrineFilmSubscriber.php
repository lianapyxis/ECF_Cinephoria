<?php

namespace App\Film\EventSubscriber\Doctrine;

use App\Film\Constant\FilmStatus;
use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Film::class)]
class DoctrineFilmSubscriber
{
    public function __construct(private readonly Security $security){

    }


    public function prePersist(Film $film, PrePersistEventArgs $eventArgs): void
    {
        /** @var Film $film */
/*        $film->setStatus(FilmStatus::DRAFT);
        $film->setUser($this->security->getUser());*/
    }
}