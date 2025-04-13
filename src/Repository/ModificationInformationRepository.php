<?php

namespace App\Repository;

use App\Entity\ModificationInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModificationInformation>
 *
 * @method ModificationInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModificationInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModificationInformation[]    findAll()
 * @method ModificationInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModificationInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModificationInformation::class);
    }

    public function save(ModificationInformation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModificationInformation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ModificationInformation[] Returns an array of ModificationInformation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModificationInformation
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findByExampleField($value): array
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.Rh = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
    ;
}
}
