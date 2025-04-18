<?php
// src/Service/CongeService.php
namespace App\Services;

use App\Entity\Conge;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CongeService
{
 
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function createConge(Conge $conge): void
    {
        $user = $this->security->getUser();
        $conge->setDatedemande(new \DateTime());
        $conge->setEtat('en cours');
        $conge->setRh($user);

        $this->entityManager->persist($conge);
        $this->entityManager->flush();
    }

    public function updateConge(Conge $conge): void
    {
        $this->entityManager->persist($conge);
        $this->entityManager->flush();
    }

    public function deleteConge(Conge $conge): void
    {
        $this->entityManager->remove($conge);
        $this->entityManager->flush();
    }

    public function acceptConge(Conge $conge): void
    {
        $conge->setEtat('Confirmer');
        $this->entityManager->persist($conge);
        $this->entityManager->flush();
    }

    public function rejectConge(Conge $conge): void
    {
        $conge->setEtat('rejeter');
        $this->entityManager->persist($conge);
        $this->entityManager->flush();
    }
    
}