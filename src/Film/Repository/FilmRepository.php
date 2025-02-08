<?php

namespace App\Film\Repository;

use App\Entity\City;
use App\Entity\Room;
use App\Entity\Seance;
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

    public function findAllNewWithPagination(int $page, $city = null): Paginator
    {
        $yesterday = date("Y-m-d", strtotime("-1 days"));
        $daybeforeyesterday = date("Y-m-d", strtotime("-2 days"));
        $wednesday = date('Y-m-d', strtotime('Wednesday'));
        $lastWednesday = date('Y-m-d', strtotime('last Wednesday'));
        if($wednesday == $yesterday OR $wednesday == $daybeforeyesterday){
            $dateCheck = $wednesday;
        } else {
            $dateCheck = $lastWednesday;
        }
        if(empty($city) OR $city == 'all'){
            $query = $this->createQueryBuilder('f')
                ->where('f.date_add LIKE :date_add')
                ->setParameter('date_add', $dateCheck.'%')
                ->orderBy('f.date_add', 'DESC');
                return new Paginator($query, $page);
        } else {
            $query = $this->createQueryBuilder('f')
                ->leftJoin(Seance::class, 's', 'WITH', 'f.id = s.id_film')
                ->leftJoin(Room::class, 'r', 'WITH', 's.id_room = r.id')
                ->leftJoin(City::class, 'c', 'WITH', 'r.id_city = c.id')
                ->where('f.date_add LIKE :date_add')
                ->andWhere('c.id = :city')
                ->setParameter('date_add', $dateCheck.'%')
                ->setParameter('city', $city)
                ->orderBy('f.date_add', 'DESC');
                return new Paginator($query, $page);
        }
    }

    public function findAllWithPagination(int $page, $city = null, $date = null): Paginator
    {
        if(empty($city) OR $city == 'all'){
            if(empty($date)){
                $query = $this->createQueryBuilder('f')
                    ->orderBy('f.date_add', 'DESC');
                    return new Paginator($query, $page);
            } else {
                $query = $this->createQueryBuilder('f')
                    ->leftJoin(Seance::class, 's', 'WITH', 'f.id = s.id_film')
                    ->where('s.time_start LIKE :date')
                    ->setParameter('date', $date.'%')
                    ->orderBy('f.date_add', 'DESC');
                    return new Paginator($query, $page);
            }
        } elseif (empty($date)) {
            $query = $this->createQueryBuilder('f')
                ->leftJoin(Seance::class, 's', 'WITH', 'f.id = s.id_film')
                ->leftJoin(Room::class, 'r', 'WITH', 's.id_room = r.id')
                ->leftJoin(City::class, 'c', 'WITH', 'r.id_city = c.id')
                ->where('c.id = :city')
                ->setParameter('city', $city)
                ->orderBy('f.date_add', 'DESC');
            return new Paginator($query, $page);
        } else {
            $query = $this->createQueryBuilder('f')
                ->leftJoin(Seance::class, 's', 'WITH', 'f.id = s.id_film')
                ->leftJoin(Room::class, 'r', 'WITH', 's.id_room = r.id')
                ->leftJoin(City::class, 'c', 'WITH', 'r.id_city = c.id')
                ->where('s.time_start LIKE :date')
                ->andWhere('c.id = :city')
                ->setParameter('date', $date.'%')
                ->setParameter('city', $city)
                ->orderBy('f.date_add', 'DESC');
            return new Paginator($query, $page);
        }
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
