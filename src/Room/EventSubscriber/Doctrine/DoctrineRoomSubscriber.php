<?php

namespace App\Room\EventSubscriber\Doctrine;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Entity\Room;
use App\Document\DamagedPlace;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DoctrineRoomSubscriber{
    public function __construct(
        private readonly DocumentManager $dm,
    ) {}

    public function postLoad(LifecycleEventArgs $eventArgs): void
    {

    }
}