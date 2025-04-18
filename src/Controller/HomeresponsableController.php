<?php

namespace App\Controller;
use App\Repository\CongeRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeresponsableController extends AbstractController
{
    #[Route('/homeresponsable', name: 'app_homeresponsable')]
    public function index(CongeRepository $congeRepository, NotificationRepository $notificationRepository): Response
    {
        $user = $this->getUser();
        
        $congeStats = $congeRepository->countbydate();
        $congedate = $congecount = [];
        $congeStats = $congeRepository->countbydate();
        $congedate = $congecount = [];
        
        foreach ($congeStats as $stat) {
            $congedate[] = $stat['date'];
            $congecount[] = $stat['count'];
        }
        
        // Notifications non lues pour le RH connecté
        $notifications = $notificationRepository->findBy([
            'recepteur' => $user,
            'is_read' => false // Utilisation de false au lieu de 0
        ], ['date_notification' => 'DESC']);
        
        return $this->render('homeresponsable/index.html.twig', [
            'congedate' => json_encode($congedate),
            'congecount' => json_encode($congecount),
            'notifications' => $notifications
        ]);
    }
}
