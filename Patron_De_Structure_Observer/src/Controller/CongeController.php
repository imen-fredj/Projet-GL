<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use App\Observer\CongeSubject;
use App\Observer\NottificationObserver;
use App\Observer\LoggingSubscriber;
use App\Repository\CongeRepository;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CongeController extends AbstractController
{
    #[Route('/conge', name: 'app_conge')]
    public function index(CongeRepository $congeRepository): Response
    {
        $user = $this->getUser();
        $conges = $congeRepository->findBy(['rh' => $user]);

        return $this->render('conge/index.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/listconge', name: 'app_listconge')]
    public function indexconge(CongeRepository $congeRepository): Response
    {
        $conges = $congeRepository->findAll();

        return $this->render('conge/indexRH.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/ajouter', name: 'app_ajoute')]
    public function ajouter(
        Request $request,
        EntityManagerInterface $manager,
        NottificationObserver $NottificationObserver,
        LoggingSubscriber $loggingSubscriber,
        NotificationRepository $notificationRepository
    ): Response {
        $user = $this->getUser();
        $conge = new Conge();
        $conge->setDatedemande(new \DateTime());
        $conge->setEtat(etat: 'en cours');
        $conge->setRh($user);

        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($conge);
            $manager->flush();

            // Notification avec le pattern Observer
            $congeSubject = new CongeSubject($conge);
            $congeSubject->attach($NottificationObserver);
            $congeSubject->attach($loggingSubscriber);
            $congeSubject->demandeCreated();

            $this->addFlash('success', 'Votre demande de congé a été envoyée');
            return $this->redirectToRoute('app_conge');
        }

        $notifications = $notificationRepository->findBy([
            'recepteur' => $this->getUser(),
            'isRead' => false
        ], ['dateNotification' => 'DESC'], 10);

        return $this->render('conge/demande.html.twig', [
            'form' => $form->createView(),
            'notifications' => $notifications
        ]);
    }

    #[Route('/accepte/{id}', name: 'accepte')]
    public function accepter(
        $id,
        EntityManagerInterface $manager,
        NottificationObserver $NottificationObserver,
        LoggingSubscriber $loggingSubscriber
    ): Response {
        $conge = $manager->getRepository(Conge::class)->find($id);

        if (!$conge) {
            throw $this->createNotFoundException('Demande de congé non trouvée');
        }

        $conge->setEtat('Confirmer');
        $manager->flush();

        // Notification avec le pattern Observer
        $congeSubject = new CongeSubject($conge);
        $congeSubject->attach($NottificationObserver);
        $congeSubject->attach($loggingSubscriber);
        $congeSubject->demandeAccepted();

        return $this->redirectToRoute('app_listconge');
    }

    #[Route('/refuse/{id}', name: 'refuse')]
    public function refuser(
        $id,
        Request $request,
        EntityManagerInterface $manager,
        NottificationObserver $NottificationObserver,
        LoggingSubscriber $loggingSubscriber
    ): Response {
        $conge = $manager->getRepository(Conge::class)->find($id);

        if (!$conge) {
            throw $this->createNotFoundException('Demande de congé non trouvée');
        }

        $conge->setEtat('rejeter');
        $manager->flush();

        // Notification avec le pattern Observer
        $congeSubject = new CongeSubject($conge);
        $congeSubject->attach($NottificationObserver);
        $congeSubject->attach($loggingSubscriber);
        $congeSubject->demandeRejected('Raison non spécifiée');

        return $this->redirectToRoute('app_listconge');
    }

    #[Route('/supprimer/{id}', name: 'app_supprime')]
    public function delete($id, EntityManagerInterface $manager): Response
    {
        $conge = $manager->getRepository(Conge::class)->find($id);

        if (!$conge) {
            throw $this->createNotFoundException('Demande de congé non trouvée');
        }

        $manager->remove($conge);
        $manager->flush();

        $this->addFlash('success', 'Demande de congé supprimée avec succès');
        return $this->redirectToRoute('app_conge');
    }

    #[Route('/modifeconge/{id}', name: 'app_modifierconge')]
    public function modifier(
        Request $request,
        $id,
        EntityManagerInterface $manager,
        NotificationRepository $notificationRepository
    ): Response {
        $conge = $manager->getRepository(Conge::class)->find($id);

        if (!$conge) {
            throw $this->createNotFoundException('Demande de congé non trouvée');
        }

        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Demande de congé modifiée avec succès');
            return $this->redirectToRoute('app_conge');
        }

        $notifications = $notificationRepository->findBy([
            'recepteur' => $this->getUser(),
            'isRead' => false
        ], ['dateNotification' => 'DESC'], 10);

        return $this->render('conge/demande.html.twig', [
            'form' => $form->createView(),
            'notifications' => $notifications
        ]);
    }
}