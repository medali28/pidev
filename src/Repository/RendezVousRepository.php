<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }
    public function findRendezVousById($Id)
    {
        try {
            return $this->createQueryBuilder('RDV')
                ->andWhere('RDV.id = :Id')
                ->setParameter('Id', $Id)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {}
    }
    public function findOneBySomeField($value): ?RendezVous
   {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Get appointments for a specific patient.
     *
     * @param int $patientId
     * @return RendezVous[]
     */
    public function getAppointmentsForPatient(int $patientId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.patient = :patientId')
            ->setParameter('patientId', $patientId)
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    /**
     * Get appointments for a specific patient.
     *
     * @param int $medecinId
     * @return RendezVous[]
     */
    public function getAppointmentsForMedecin(int $medecinId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.medecin= :medecinId')
            ->setParameter('medecinId', $medecinId)
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Get appointments for a specific patient.
     *
     * @param int $expertId
     * @return RendezVous[]
     */
    public function getAppointmentsForExpert(int $expertId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.expert= :expertId')
            ->setParameter('expertId', $expertId)
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAppointmentsToRemind()
    {
        $twoDaysLater = new \DateTime('now +2 days');

        return $this->createQueryBuilder('r')
            ->where('r.date = :date')
            ->andWhere('r.time <= :time')
            ->setParameter('date', $twoDaysLater->format('Y-m-d'))
            ->setParameter('time', $twoDaysLater->format('H:i:s'))
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return RendezVous[] Returns an array of RendezVous objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RendezVous
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
