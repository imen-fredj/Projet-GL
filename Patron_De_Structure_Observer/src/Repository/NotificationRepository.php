<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Notification[] Returns an array of Notification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notification
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    
public function findnotif($value, $value1){
    $query=$this->createQueryBuilder('a')
    ->select('COUNT(a) as count')
    ->andWhere('a.is_read= :val')
    ->setParameter('val', $value)
    ->andWhere('a.recepteur = :val1')
    ->setParameter('val1', $value1);
    return $query->getQuery()->getResult();
    
    }
    public function findbyemploye($userId, $isRead)
    {
        return $this->createQueryBuilder('n')
            ->where('n.recepteur = :userId')
            ->andWhere('n.is_read = :isRead')
            ->setParameter('userId', $userId)
            ->setParameter('isRead', $isRead)
            ->groupBy('n.text, n.date_notification') // Group by content and date
            ->orderBy('n.date_notification', 'DESC')
            ->getQuery()
            ->getResult();
    }
// In Notification.php
public function setRecepteur($recepteur): self
{
    $this->recepteur = $recepteur;
    return $this;
}


}