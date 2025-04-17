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
use App\Factory\NotificationFactory;

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

    #[Route('/ajouter', name: 'app_ajoute')]
    public function ajouter(
        Request $request,
        EntityManagerInterface $manager,
        NotificationFactory $notificationFactory
    ): Response {
        $user = $this->getUser();
        $conge = new Conge();
        $conge->setDatedemande(new \DateTime());
        $conge->setEtat('en cours');
        $conge->setRh($user);

        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($conge);

            $notification = $notificationFactory->create(
                $user->getPrenom() . ' ' . $user->getNom() . ' a demandé un congé',
                $user, // recepteur
                $user, // destinateur
                $conge
            );

            $manager->persist($notification);
            $manager->flush();

            $this->addFlash('success', 'Votre demande de congé a été envoyée');
            return $this->redirectToRoute('app_conge');
        }

        return $this->render('conge/demande.html.twig', [
            'for' => $form->createView(),
        ]);
    }
 

    #[Route('/accepte/{id}', name: 'accepteconge')]
    public function accepter(
        $id,
        EntityManagerInterface $manager,
        NotificationFactory $notificationFactory
    ): Response {
        $conge = $manager->getRepository(Conge::class)->find($id);
        $conge->setEtat('Confirmer');

        $currentUser = $this->getUser();

        $notification = $notificationFactory->create(
            'Votre congé a été accepté',
            $conge->getRh(),
            $currentUser,
            $conge
        );

        $manager->persist($notification);
        $manager->persist($conge);
        $manager->flush();

        return $this->redirectToRoute('app_listconge');
    }
    #[Route('/refuse/{id}', name: 'refuseconge')]
    public function refuser(
        $id,
        EntityManagerInterface $manager,
        NotificationFactory $notificationFactory
    ): Response {
        $conge = $manager->getRepository(Conge::class)->find($id);
        $conge->setEtat('rejeter');

        $notification = $notificationFactory->create(
            'Votre congé a été refusé',
            $conge->getRh(),
            $this->getUser(),
            $conge
        );

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
    public function modifier(Request $request, $id, EntityManagerInterface $em): Response
    {
        $conge = $this->getDoctrine()->getRepository(Conge::class)->find($id);
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
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