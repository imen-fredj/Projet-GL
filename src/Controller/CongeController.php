<?php

namespace App\Controller;

use App\Repository\TypecongeRepository;
use App\Repository\NotificationRepository;
use App\Entity\Conge;
use App\Entity\Typeconge;
use App\Entity\Notification;
use App\Repository\CongeRepository;
use App\Form\CongeType;
use App\Entity\Rh;
use App\Repository\RhRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use App\Services\CongeService;
use App\Services\NotificationService;
class CongeController extends AbstractController
{
    private $congeService;
    private $notificationService;

    public function __construct(CongeService $congeService, NotificationService $notificationService)
    {
        $this->congeService = $congeService;
        $this->notificationService = $notificationService;
    }

    #[Route('/conge', name: 'app_conge')]
    public function index(CongeRepository $congeRepository): Response
    {   
        $user = $this->getUser();
        $conges = $congeRepository->findByExampleField($user->getId());
        
        return $this->render('conge/index.html.twig', [
            'cong' => $conges,
        ]);
    }

    #[Route('/listconge', name: 'app_listconge')]
    public function listConge(CongeRepository $congeRepository): Response
    {  
        $conges = $congeRepository->findAll();
        
        return $this->render('conge/indexRH.html.twig', [
            'cong' => $conges,
        ]);
    }
    #[Route('/ajouter', name: 'app_ajoute')]
public function ajouter(
    Request $request,
    CongeService $congeService,
    NotificationService $notificationService
): Response {
    $conge = new Conge();
    $form = $this->createForm(CongeType::class, $conge);
    $form->handleRequest($request); 

    if($form->isSubmitted() && $form->isValid()) {
        // Crée le congé
        $congeService->createConge($conge);
        
        // Envoie la notification à tous les RH
        $notificationService->notifyAllRh(
            $conge, 
            $this->getUser()->getPrenom().' '.$this->getUser()->getNom().' a demandé un congé'
        );
        
        $this->addFlash('success', 'Votre demande de congé a été envoyée');
        return $this->redirectToRoute('app_conge');
    }

    return $this->render('conge/demande.html.twig', [
        'for' => $form->createView(),
    ]);
}

    #[Route('/accepte/{id}', name: 'accepteconge')]
    public function accepter(Conge $conge): Response
    {   
        $this->congeService->acceptConge($conge);
        $this->notificationService->createCongeNotification(
            $conge, 
            'Votre congé a été accepté',
            1 // ou true selon votre logique métier
        );
        
        return $this->redirectToRoute('app_listconge');
    }
    
    #[Route('/refuse/{id}', name: 'refuseconge')]
    public function refuser(Conge $conge): Response
    {    
        $this->congeService->rejectConge($conge);
        $this->notificationService->createCongeNotification(
            $conge, 
            'Votre congé a été refusé',
            0 // Marqué comme non lu
        );
        
        return $this->redirectToRoute('app_listconge');
    }
   
}


   