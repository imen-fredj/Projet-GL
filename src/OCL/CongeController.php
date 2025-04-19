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

class CongeController extends AbstractController
{
    #[Route('/conge', name: 'app_conge')]
    public function index(CongeRepository $congeRepository): Response
    {   
        $user = $this->getUser();
        $type = $congeRepository->findByExampleField($user->getId());
        return $this->render('conge/index.html.twig', [
            'cong' => $type,
        ]);
    }

    #[Route('/listconge', name: 'app_listconge')]
    public function indexconge(CongeRepository $congeRepository): Response
    {  
        $type = $congeRepository->findAll();
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

            $notification = new Notification();
            $notification->setText($user->getPrenom().' '.$user->getNom().' a demandé un congé');
            $notification->setDateNotification(new \DateTime());
            $notification->setRecepteur($user);
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
     * @Route("/accepte/{id}", name="accepteconge")
     */
    public function accepter($id, EntityManagerInterface $manager): Response
    {   
        $conge = $this->getDoctrine()->getRepository(Conge::class)->find($id);
        $conge->setEtat('Confirmer');
        
        $notification = new Notification();
        $notification->setText('votre congé a été accepter');
        $notification->setRecepteur($conge->getRh());
        $currentUser = $this->getUser();
        $notification->setDateNotification(new \DateTime()); 
        $notification->setDestinateur($currentUser);
        $notification->setConge($conge);
        
        $manager->persist($notification);
        $manager->persist($conge);
        $manager->flush();
        
        return $this->redirectToRoute('app_listconge');
    }

    /**
     * @Route("/refuse/{id}", name="refuseconge")
     */
    public function refuser($id, EntityManagerInterface $manager): Response
    {    
        $conge = $this->getDoctrine()->getRepository(Conge::class)->find($id);
        $conge->setEtat('rejeter');
        
        $notification = new Notification();
        $notification->setText('votre congé a été refuser');
        $notification->setIsRead(0);
        $notification->setRecepteur($conge->getRh());
        
        $manager->persist($notification);
        $manager->persist($conge);
        $manager->flush();
        
        return $this->redirectToRoute('app_listconge');
    }

//  /**
//  * @Route("/supprimer/{id}", name="app_supprime")
//  */
// public function delete($id, EntityManagerInterface $manager): Response
// { dump($id);
//     $cong = $manager->getRepository(Conge::class)->find($id);
//     dump($cong);
     
//     if (!$cong) {
//         $this->addFlash('error', 'Demande de congé non trouvée (ID: '.$id.')');
//         return $this->redirectToRoute('app_conge');
//     }
//     $manager->remove($cong);
//     $manager->flush();

//     $this->addFlash('success', 'Suppression réussie.');
//     return $this->redirectToRoute('app_conge');
// }




    /**
     * @Route("/modifeconge/{id}", name="app_modifierconge")
     */
    /**
 * @Route("/modifeconge/{id}", name="app_modifierconge")
 */
public function modifier(Request $request, $id, EntityManagerInterface $em): Response
{
    $conge = $this->getDoctrine()->getRepository(Conge::class)->find($id);
    
    // Vérification de la précondition OCL
    if ($conge->getEtat() !== 'en cours') {
        $this->addFlash('error', 'Seuls les congés en attente peuvent être modifiés');
        return $this->redirectToRoute("app_conge");
    }

    $form = $this->createForm(CongeType::class, $conge);
    $form->handleRequest($request);
    
    if($form->isSubmitted() && $form->isValid()) {
        // Recalcul du nombre de jours si les dates ont changé
        $diff = $conge->getDatedebut()->diff($conge->getDatefin());
        $conge->setNbjour($diff->days);
        
        $em->persist($conge);
        $em->flush();
        $this->addFlash('success', 'Demande modifiée avec succès');
        return $this->redirectToRoute("app_conge");
    }
    
    return $this->render('conge/demande.html.twig', [
        'for' => $form->createView(),
    ]);
}

}