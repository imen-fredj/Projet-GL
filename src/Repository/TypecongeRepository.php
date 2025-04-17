<?php
// src/Repository/TypecongeRepository.php
namespace App\Repository;

use App\Entity\Typeconge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TypecongeRepository extends ServiceEntityRepository implements TypecongeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeconge::class);
    }

    public function save(Typeconge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Typeconge $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countRelatedConges(Typeconge $typeconge): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(c.id)')
            ->join('t.conges', 'c')
            ->where('t.id = :id')
            ->setParameter('id', $typeconge->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }
}