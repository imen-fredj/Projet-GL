<?php
namespace App\Services;

use App\Entity\Notification;
use App\Entity\Conge;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use App\Repository\RhRepository;

class NotificationService
{
    private $entityManager;
    private $security;
    private $roleHierarchy;
    private $rhRepository;

    public function __construct(
        EntityManagerInterface $entityManager, 
        Security $security,
        RoleHierarchyInterface $roleHierarchy,
        RhRepository $rhRepository
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->roleHierarchy = $roleHierarchy;
        $this->rhRepository = $rhRepository;
    }
    
    public function createCongeNotification(Conge $conge, string $message): void
    {
        $user = $this->security->getUser();
        
        $notification = new Notification();
        $notification->setText($message);
        $notification->setDateNotification(new \DateTime());
        $notification->setRecepteur($conge->getRh());
        $notification->setDestinateur($user);
        $notification->setConge($conge);
        $notification->setIsRead(false);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    public function notifyAllRh(Conge $conge, string $message): void
    {
        $currentUser = $this->security->getUser();
        $rhUsers = $this->rhRepository->findByRole('ROLE_Rh'); // Correction de la casse
    
        foreach ($rhUsers as $rhUser) {
            // Vérification que le RH n'est pas l'expéditeur
            if ($rhUser->getId() !== $currentUser->getId()) {
                $notification = new Notification();
                $notification->setText($message);
                $notification->setDateNotification(new \DateTime());
                $notification->setRecepteur($rhUser);
                $notification->setDestinateur($currentUser);
                $notification->setConge($conge);
                $notification->setIsRead(0); // Utilisation de false au lieu de 0
    
                $this->entityManager->persist($notification);
            }
        }
    
        $this->entityManager->flush();
    }
}