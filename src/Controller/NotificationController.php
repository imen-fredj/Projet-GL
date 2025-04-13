<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NotificationRepository;
class NotificationController extends AbstractController
{
    
 /**
     * @Route("/notification/read/{id}", name="notification_read")
     */
    public function markAsRead(Notification $notification, EntityManagerInterface $manager): Response
    {
        if ($notification->getRecepteur() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $notification->setIsRead(true);
        $manager->flush();

        return $this->redirectToRoute('app_homeresponsable');
    }
}
