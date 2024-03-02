<?php

namespace App\Repository;

use App\Entity\ProgressBar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgressBar>
 *
 * @method ProgressBar|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProgressBar|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProgressBar[]    findAll()
 * @method ProgressBar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgressBarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgressBar::class);
    }

//    /**
//     * @return ProgressBar[] Returns an array of ProgressBar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProgressBar
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
