<?php

namespace App\City\Repository;

use App\Entity\City;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<City>
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, City::class);
    }

    public function findByUser(User $user): mixed
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('a')
            /*            ->where('a.status = :published')*/
            /*            ->orWhere('a.user = :user')*/
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return City[] Returns an array of City objects
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

    //    public function findOneBySomeField($value): ?City
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
