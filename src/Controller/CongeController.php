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
use App\Controller\AbstractDemandeController;


class CongeController extends AbstractDemandeController
{
    #[Route('/conge', name: 'app_conge')]
    public function index(CongeRepository $repository): Response
    {
        return $this->handleIndex('findByExampleField');
    }
    
    protected function getIndexTemplate(): string
    {
        return 'conge/index.html.twig';
    }
    
    protected function getEntityContextName(): string
    {
        return 'cong';
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

     public function ajouter(Request $request): Response
     {
         return $this->handleAdd(
             $request,
             'app_conge'
         );
     }

protected function createNewEntity()
{
    return new Conge();
}

protected function getFormType(): string
{
    return CongeType::class;
}

protected function postPersist($entity, EntityManagerInterface $em): void
{
    // Création de la notification spécifique aux congés
    $notification = new Notification();
    $user = $this->getUser();
    $notification->setText($user->getPrenom().' '.$user->getNom().' a demandé un congé');
    $notification->setDateNotification(new \DateTime());
    $notification->setRecepteur($user);
    $notification->setDestinateur($user);
    $notification->setConge($entity);
    
    $em->persist($notification);

        // Add flash message here instead
        $this->addFlash('success', 'Votre demande de congé a été envoyée');
}

    /**
     * @Route("/accepte/{id}", name="accepteconge")
     */
    public function accepter($id, EntityManagerInterface $manager): Response
    {
        return $this->handleAccept(
            $id,
            $manager,
            Conge::class,
            'Confirmer',
            'app_listconge'
        );
    }
    
    protected function updateEntityState($entity, string $successState): void
    {
        $entity->setEtat($successState);
    }
    
    protected function createAcceptNotification($entity, EntityManagerInterface $manager): void
    {
        $notification = new Notification();
        $notification->setText('votre congé a été accepté');
        $notification->setRecepteur($entity->getRh());
        $notification->setDestinateur($this->getUser());
        $notification->setDateNotification(new \DateTime());
        $notification->setConge($entity);
        
        $manager->persist($notification);
    }
    /**
     * @Route("/refuse/{id}", name="refuseconge")
     */
    public function refuser($id, EntityManagerInterface $manager): Response
    {
        return $this->handleReject($id, $manager, Conge::class, 'rejeter','app_listconge');
    }
    
    protected function updateEntityOnReject($entity, string $rejectedState, EntityManagerInterface $manager): void
    {
        $entity->setEtat($rejectedState);
        $manager->persist($entity);
    }
    
    protected function createRejectionNotification($entity, EntityManagerInterface $manager): void
    {
        $notification = new Notification();
        $notification->setText('votre congé a été refusé');
        $notification->setIsRead(0);
        $notification->setRecepteur($entity->getRh());
        $manager->persist($notification);
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

    public function modifier(Request $request, $id): Response
    {
        return $this->handleEdit($request, $id, 'app_conge');
    }
    
    protected function postEditSuccess($entity, Request $request): void
    {
        $this->addFlash('success', 'Demande modifiée avec succès');
    }
    
    protected function getFormTemplate(): string
    {
        return 'conge/demande.html.twig';
    }

    protected function getEntityClass(): string
{
    return Conge::class;
}
protected function preEditPersist($entity, EntityManagerInterface $em): void
{

}

}