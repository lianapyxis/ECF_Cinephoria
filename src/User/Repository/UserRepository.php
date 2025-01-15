<?php

namespace App\User\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

        /**
         * @return User[] Returns an array of User objects
         */
        public function findStaff(): array
        {

            $conn = $this->getEntityManager()->getConnection();

            $sql = '
            SELECT * FROM user u
            WHERE u.roles LIKE \'%ROLE_WORKER%\'
            ORDER BY u.id ASC
            ';

            $resultSet = $conn->executeQuery($sql);

            return $resultSet->fetchAll();

/*            $value = '[ROLE_WORKER]';
            return $this->createQueryBuilder('c')
                ->andWhere('c.roles = :val')
                ->setParameter('val', $value)
                ->orderBy('c.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;*/
        }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
