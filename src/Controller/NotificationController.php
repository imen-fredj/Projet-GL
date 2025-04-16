<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification/read/{id}", name="notification_read")
     */
    public function markAsRead(
        Notification $notification, 
        EntityManagerInterface $manager,
        Request $request
    ): Response {
        // Vérification de sécurité
        if ($notification->getRecepteur()->getId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }

        // Marquage comme lue
        $notification->setIsRead(1);
        $manager->flush();

        // Redirection intelligente
        if ($request->isXmlHttpRequest()) {
            return new Response(null, 204);
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('app_homeresponsable'));
    }
}