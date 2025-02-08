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

        }

        public function findOneByEmail($email): ?User
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.email = :val')
                ->setParameter('val', $email)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
}
