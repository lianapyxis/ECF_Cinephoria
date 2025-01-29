<?php

namespace App\Film\Repository;

use App\Entity\Film;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use App\Model\Paginator;

/**
 * @extends ServiceEntityRepository<Film>
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, Film::class);
    }

    public function findAllWithPagination(int $page): Paginator
    {
        $query = $this->createQueryBuilder('t')->orderBy('t.date_add', 'DESC');
        return new Paginator($query, $page);
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
    //     * @return Film[] Returns an array of Film objects
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

    //    public function findOneBySomeField($value): ?Film
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
