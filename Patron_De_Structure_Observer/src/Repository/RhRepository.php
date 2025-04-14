<?php

// src/Repository/RhRepository.php
namespace App\Repository;

use App\Entity\Rh;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RhRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rh::class);
    }
}

// use App\Entity\Rh;
// use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Persistence\ManagerRegistry;
// use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
// use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
// use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

// /**
//  * @extends ServiceEntityRepository<Rh>
//  *
//  * @method Rh|null find($id, $lockMode = null, $lockVersion = null)
//  * @method Rh|null findOneBy(array $criteria, array $orderBy = null)
//  * @method Rh[]    findAll()
//  * @method Rh[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
//  */
// class RhRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
// {
//     public function __construct(ManagerRegistry $registry)
//     {
//         parent::__construct($registry, Rh::class);
//     }

//     public function save(Rh $entity, bool $flush = false): void
//     {
//         $this->getEntityManager()->persist($entity);

//         if ($flush) {
//             $this->getEntityManager()->flush();
//         }
//     }

//     public function remove(Rh $entity, bool $flush = false): void
//     {
//         $this->getEntityManager()->remove($entity);

//         if ($flush) {
//             $this->getEntityManager()->flush();
//         }
//     }

//     /**
//      * Used to upgrade (rehash) the user's password automatically over time.
//      */
//     public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
//     {
//         if (!$user instanceof user) {
//             throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
//         }

//         $user->setPassword($newHashedPassword);

//         $this->save($user, true);
//     }

//     /**
//      * @return Rh[] Returns an array of Rh objects
//      */
//     public function findBycin($value): array
//     {
//         return $this->createQueryBuilder('r')
//             ->andWhere('r.cin = :val')
//             ->setParameter('val', $value)

//             ->getQuery()
//             ->getResult();
//     }

//     public function findByRole(string $role)
//     {
//         return $this->createQueryBuilder('r')
//             ->andWhere('r.roles LIKE :role')
//             ->setParameter('role', '%"' . $role . '"%')
//             ->getQuery()
//             ->getResult();
//     }

//     //public function findOneBySomeField($value): ?Rh
//     // {
//     //     return $this->createQueryBuilder('r')
//     //        ->andWhere('r.cin = :val')
//     //        ->setParameter('val', $value)
//     //       ->getQuery()
//     //       ->getOneOrNullResult()
//     //    ;
//     //  }
//     public function findByExampleField($value): array
//     {
//         return $this->createQueryBuilder('e')
//             ->andWhere('e.id = :val')
//             ->setParameter('val', $value)
//             ->getQuery()
//             ->getResult()
//         ;
//     }
// }