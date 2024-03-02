<?php

namespace App\Repository;

use App\Entity\Cnam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cnam>
 *
 * @method Cnam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cnam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cnam[]    findAll()
 * @method Cnam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CnamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cnam::class);
    }

//    /**
//     * @return Cnam[] Returns an array of Cnam objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cnam
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
