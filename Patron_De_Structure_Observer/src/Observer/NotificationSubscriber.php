<?php

// src/Observer/NotificationSubscriber.php
namespace App\Observer;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Conge;

class NotificationSubscriber implements SubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update(string $event, array $data = []): void
    {
        $conge = $data['conge'];
        $message = $data['message'] ?? '';
        
        switch ($event) {
            case 'conge.created':
                $text = sprintf(
                    "%s %s a demandé un congé: %s",
                    $conge->getRh()->getPrenom(),
                    $conge->getRh()->getNom(),
                    $message
                );
                $this->createNotification($conge, $text);
                break;
                
            case 'conge.accepted':
                $text = sprintf(
                    "Votre congé a été accepté: %s",
                    $message
                );
                $this->createNotification($conge, $text);
                break;
                
            case 'conge.rejected':
                $reason = $data['reason'] ?? '';
                $text = sprintf(
                    "Votre congé a été refusé (%s): %s",
                    $reason,
                    $message
                );
                $this->createNotification($conge, $text);
                break;
        }
    }

    private function createNotification(Conge $conge, string $text): void
    {
        $notification = new Notification();
        $notification->setText($text);
        $notification->setDateNotification(new \DateTime());
        $notification->setRecepteur($conge->getRh());
        $notification->setDestinateur($conge->getRh()); // Ou l'admin selon votre logique
        $notification->setConge($conge);
        $notification->setIsRead(false);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}