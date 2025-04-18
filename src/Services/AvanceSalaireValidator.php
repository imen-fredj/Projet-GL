<?php
// src/Service/AvanceSalaireValidator.php
namespace App\Services;

use App\Entity\AvanceSalaire;
use App\Entity\Rh;
use Doctrine\ORM\EntityManagerInterface;

class AvanceSalaireValidator
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function canRequestAdvance(Rh $user, \DateTimeInterface $date): bool
    {
        $debutMois = new \DateTime($date->format('Y-m-01'));
        $finMois = new \DateTime($date->format('Y-m-t 23:59:59'));
        
        $count = $this->em->getRepository(AvanceSalaire::class)
            ->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.Rh = :user')
            ->andWhere('a.date_avance BETWEEN :debut AND :fin')
            ->andWhere('(a.etat != :rejete OR a.etat IS NULL)')
            ->setParameter('user', $user)
            ->setParameter('debut', $debutMois)
            ->setParameter('fin', $finMois)
            ->setParameter('rejete', 'rejeter')
            ->getQuery()
            ->getSingleScalarResult();
            
        return $count < 2;
    }
}