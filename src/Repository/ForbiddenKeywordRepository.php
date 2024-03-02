<?php

namespace App\Repository;

use App\Entity\ForbiddenKeyword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForbiddenKeyword>
 *
 * @method ForbiddenKeyword|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForbiddenKeyword|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForbiddenKeyword[]    findAll()
 * @method ForbiddenKeyword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForbiddenKeywordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForbiddenKeyword::class);
    }

//    /**
//     * @return ForbiddenKeyword[] Returns an array of ForbiddenKeyword objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ForbiddenKeyword
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
