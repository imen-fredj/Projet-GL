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
    public function index(CongeRepository $CongeRepository,NotificationRepository $notificationRepository): Response
    {  $conge=$CongeRepository->countbydate();
        $congedate=[];
        $congecount=[];
        
        foreach (  $conge as  $cg){

            $congedate[]=$cg['date'];
            $congecount[]=$cg['count'];
           
             }
             $notifications = $notificationRepository->findBy([
                'recepteur' => $this->getUser(),
            ], ['dateNotification' => 'DESC'], 10);
            
        return $this->render('homeresponsable/index.html.twig', [
            'congedate'=>json_encode($congedate),
            'congecount'=>json_encode($congecount),
          'notifications' => $notifications
        ]);
    }
}
