<?php

namespace App\Repository;

use App\Entity\Conge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conge>
 *
 * @method Conge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conge[]    findAll()
 * @method Conge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conge::class);
    }

    public function save(Conge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Conge[] Returns an array of Conge objects
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

//    public function findOneBySomeField($value): ?Conge
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
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

/**
 * @return void
 */
public function countbydate(){
    $query=$this->createQueryBuilder('a')
    ->select('SUBSTRING(a.datedemande,1,7)as date,COUNT(a) as count')
    ->groupBy('date');
    return $query->getQuery()->getResult();
    
    }
    /**
 * @return void
 */
public function countdate($value){
    $query=$this->createQueryBuilder('a')
    ->select('SUBSTRING(a.datedemande,1,7)as date,COUNT(a) as count')
    ->groupBy('date')
    ->andWhere('a.Rh= :val')
    ->setParameter('val', $value);
    return $query->getQuery()->getResult();
    
    }
}
