<?php

namespace App\Seance\Repository;

use App\Entity\Seance;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Seance>
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, Seance::class);
    }

    public function getCity(Seance $seance): mixed
    {
        $room = $seance->getIdRoom();
        $city = $room->getIdCity();
        $cityTitle = $city->getTitle();
        return $cityTitle;

/*        return $this->createQueryBuilder('a')*/
            /*            ->where('a.status = :published')*/
            /*            ->orWhere('a.user = :user')*/
/*            ->select('title')
            ->from('city', 'c')
            ->where('a.id = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();*/
    }

    //    /**
    //     * @return Seance[] Returns an array of Seance objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Seance
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
