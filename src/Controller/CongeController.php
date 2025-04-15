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


use Symfony\Component\Security\Core\Security; // Add this line
class CongeController extends AbstractController
{
    #[Route('/conge', name: 'app_conge')]
    public function index(CongeRepository $CongeRepository): Response
    {   $user = $this->getUser();
        $conge= $this->getDoctrine()->getManager();
        $type= $conge->getRepository(conge::class)->findByExampleField($user->getId());
        return $this->render('conge/index.html.twig', [
            'cong' => $type,
        ]);
    }
    #[Route('/listconge', name: 'app_listconge')]
    public function indexconge(CongeRepository $CongeRepository): Response
    {  
        $conge= $this->getDoctrine()->getManager();
        $type= $conge->getRepository(conge::class)->findAll();
   
        return $this->render('conge/indexRH.html.twig', [
            'cong' => $type,
        ]);
    }
/**
 * @Route("/ajouter", name="app_ajoute")
 */
public function ajouter(Request $request, EntityManagerInterface $manager, NotificationRepository $notificationRepository): Response
{
    $user = $this->getUser();
    $conge = new Conge();
    $conge->setDatedemande(new \DateTime());
    $conge->setEtat('en cours');
    $conge->setRh($user);

    $form = $this->createForm(CongeType::class, $conge);
    $form->handleRequest($request); 

    if($form->isSubmitted() && $form->isValid()) {
        $manager->persist($conge);
        $manager->flush();

        // Créer la notification
        $notification = new Notification();
        $notification->setText($user->getPrenom().' '.$user->getNom().' a demandé un congé');
        $notification->setDateNotification(new \DateTime());
        $notification->setRecepteur($user); // À adapter selon votre logique
        $notification->setDestinateur($user);
        $notification->setConge($conge);
        
        $manager->persist($notification);
        $manager->flush();
    
        $this->addFlash('success', 'Votre demande de congé a été envoyée');
        return $this->redirectToRoute('app_conge');
    }


    return $this->render('conge/demande.html.twig', [
        'for' => $form->createView(),
    ]);
}
    /**
     * @Route("/accepte/{id}",name="accepte")
     
     */
    public function accepter($id, Request $request, EntityManagerInterface $manager): Response
    {   $conge=$this->getDoctrine()->getRepository(conge::class)->find($id);
        $conge->setEtat('Confirmer');
        $conge->getRh();
        $Notification=new Notification();
        $Notification->setText('votre congé a été accepter');
        $Notification->setRecepteur($conge->getRh());
        $currentUser = $this->getUser();
        $Notification->setDateNotification(new \DateTime()); 
            $Notification->setDestinateur($currentUser);
        
        $Notification->setConge($conge); // ou $type si dans ajouter()
        $manager->persist($Notification);
        $manager->flush();
        $manager->persist($conge);
        $manager->flush();
        return $this->redirectToRoute('app_listconge');
}
  /**
     * @Route("/refuse/{id}",name="refuse")
     
     */
    public function refuser($id, Request $request, EntityManagerInterface $manager): Response
    {    $repo=$this->getDoctrine()->getRepository(conge::class );
        $conge=$repo->find($id);
        $conge->setEtat('rejeter');
        $Notification=new Notification();
        $Notification->setText('votre congé a été refuser');
        $Notification->setIsRead(0);
        $manager->persist($Notification);
        $manager->flush();
        $Notification->setRecepteur($conge->getRh());
        $manager->persist($conge);
        $manager->flush();
        return $this->redirectToRoute('app_listconge');
}
/**
     * 
     * @Route("/supprimer/{id}" , name="app_supprime")
     */

    function Delete($id):Response
    {
        $repo=$this->getDoctrine()->getRepository(conge::class );
        $conge=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($conge);
        $em->flush();
        return $this->redirectToRoute('app_conge');
    }
    /**
     * @Route("/modifeconge/{id}",name="app_modifierconge")
     */
    public function modifier(\Symfony\Component\HttpFoundation\Request $request ,$id): Response
    {
        $conge=$this->getDoctrine()->getRepository(conge::class)->find($id);
      
        $form=$this->createForm(CongeType::class,$conge);
        
        $form->handleRequest($request);
        if($form->isSubmitted() )
        {
           
            $em=$this->getDoctrine()->getManager();
          
            $em->persist($conge);
            $em->flush();
            return $this->redirectToRoute("app_conge");
    }
    return $this->render('conge/demande.html.twig', [
        'for'=>$form->createView(),

    ]);
    }
}
