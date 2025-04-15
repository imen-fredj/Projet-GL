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
    public function index(CongeRepository $CongeRepository): Response
    {  $conge=$CongeRepository->countbydate();
        $congedate=[];
        $congecount=[];
         $user = $this->getUser();
    $notifications = $manager->getRepository(Notification::class)
        ->findBy(['recepteur' => $user, 'is_read' => 0]);
        foreach (  $conge as  $cg){

            $congedate[]=$cg['date'];
            $congecount[]=$cg['count'];
           
             }
           
            
        return $this->render('homeresponsable/index.html.twig', [
            'congedate'=>json_encode($congedate),
            'congecount'=>json_encode($congecount),
            'notifications' => $notifications

        ]);
    }
}
