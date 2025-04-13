<?php

namespace App\Controller;
use App\Entity\Conge;
use App\Entity\Notification;
use App\Repository\CongeRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class HomeemplController extends AbstractController
{
    #[Route('/homeempl', name: 'app_homeempl')]
    public function index(CongeRepository $CongeRepository): Response
    {   $user = $this->getUser();
        $conge= $this->getDoctrine()->getManager();
        $type= $conge->getRepository(conge::class)->countdate($user->getId());
        $congedate=[];
        $congecount=[];
       
        foreach (  $type as  $cg){

            $congedate[]=$cg['date'];
            $congecount[]=$cg['count'];
         
             }
            
        return $this->render('homeempl\index.html.twig', [
            'congedate'=>json_encode($congedate),
            'congecount'=>json_encode($congecount)
        ]);
     
    }
    #[Route('/emp', name: 'app_empl')]
    public function count(NotificationRepository $NotificationRepository,EntityManagerInterface $manager): Response
    {   $user = $this->getUser();
        $notif= $this->getDoctrine()->getManager();
        $not= $NotificationRepository->findnotif(0,$user->getId());
        $notification= $NotificationRepository->findbyemploye($user->getId(),0);
        forEach ($notification as $nott){
        $nott->setIsRead(1);
        $manager->persist($nott);
        $manager->flush();
        }
     
       
        return $this->render('baseEmp.html.twig', [
            'notification'=>$not,
            'listenotification'=>$notification
        ]);
    
    }
}
