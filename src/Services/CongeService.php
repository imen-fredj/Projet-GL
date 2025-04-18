<?php
// src/Service/CongeService.php
namespace App\Services;

use App\Entity\Conge;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CongeService
{
    public const STATUS_PENDING = 'en cours';
    public const STATUS_APPROVED = 'Confirmer';
    public const STATUS_REJECTED = 'rejeter';
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

   
    public function changeCongeStatus(Conge $conge, string $status): void
    {
        $validStatuses = [
            self::STATUS_PENDING,
            self::STATUS_APPROVED, 
            self::STATUS_REJECTED
        ];
        
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException(sprintf(
                'Statut invalide "%s". Statuts valides: %s',
                $status,
                implode(', ', $validStatuses)
            ));
        }
        
        $conge->setEtat($status);
        $this->entityManager->persist($conge);
        $this->entityManager->flush();
    }

    // Méthodes explicites pour le contrôleur
    public function approveConge(Conge $conge): void
    {
        $this->changeCongeStatus($conge, self::STATUS_APPROVED);
    }

    public function rejectConge(Conge $conge): void
    {
        $this->changeCongeStatus($conge, self::STATUS_REJECTED);
    }
}