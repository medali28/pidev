<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneBySpecialite($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.specialite = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findOneByAddress($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.address = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function findMedecins(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_MEDECIN"%')
            ->getQuery()
            ->getResult();
    }
    public function findMedecinsPrix(int $price): array
    {
         $range = 10 ;
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('u.prix_c >= :minPrice')
            ->andWhere('u.prix_c <= :maxPrice')
            ->setParameter('role', '%"ROLE_MEDECIN"%')
            ->setParameter('minPrice', $price - $range)
            ->setParameter('maxPrice', $price + $range)
            ->getQuery()
            ->getResult();
    }

    public function findMedecinsBySpecialite(string $specialite): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->andWhere('u.specialite = :specialite')
            ->setParameter('role', '%"ROLE_MEDECIN"%')
            ->setParameter('specialite', $specialite)
            ->getQuery()
            ->getResult();
    }

    public function findMedecinsByCriteria($specialite, $prix ,$pays , $ville)
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->andWhere('m.roles LIKE :role')
            ->setParameter('role', '%"ROLE_MEDECIN"%');
        if ($specialite  !== 'None'){
            $queryBuilder
            ->andWhere('m.specialite = :specialite')
                ->setParameter('specialite', $specialite);
        }
        if ($pays  !== 'none'){
            $queryBuilder
                ->andWhere('m.pays = :pays')
                ->setParameter('pays', $pays);
        }
        if ($ville  !== 'none'){
            $queryBuilder
                ->andWhere('m.ville = :ville')
                ->setParameter('ville', $ville);
        }
        if ($prix !== null) {
            $range = 10 ;
            $queryBuilder
                ->andWhere('m.prix_c >= :minPrice')
                ->andWhere('m.prix_c <= :maxPrice')
                ->setParameter('minPrice', $prix - $range)
                ->setParameter('maxPrice', $prix + $range);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
