<?php

namespace App\Controller;
use App\Repository\RhRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationFormMType;
use App\Entity\Rh;
use App\Entity\Conge;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request; 
use App\Repository\NotificationRepository;
use App\Entity\Notification;

class HomeController extends AbstractController
{#[Route('/home', name: 'app_home')]
    public function index(Request $request, RhRepository $RhRepository, PaginatorInterface $paginator, NotificationRepository $NotificationRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            return $this->render('homeadmin/index.html.twig', []);
        }
        
        if ($user->getRoles() == ["ROLE_Rh"]) {
            if ($user->getActive() == "False") {
                $this->addFlash('error', "Votre compte n'est pas encore activé");
                return $this->redirectToRoute('app_login');
            } else {
                // Création de la requête de base
                $query = $em->getRepository(Rh::class)->createQueryBuilder('r')
                    ->where('r.active = :active')
                    ->orderBy('r.nom', 'ASC')
                    ->setParameter('active', 'True');
                
                // Gestion de la recherche par CIN
                if ($request->isMethod("POST")) {
                    $cin = $request->get('cin');
                    if ($cin) {
                        $query->andWhere('r.cin = :cin')
                            ->setParameter('cin', $cin);
                    }
                }
                
                // Calcul dynamique du nombre d'éléments par page
                $totalUsers = $em->getRepository(Rh::class)->count(['active' => 'True']);
                $perPage = $totalUsers > 10 ? 10 : ($totalUsers > 0 ? $totalUsers : 1);
                
                // Pagination
                $personne = $paginator->paginate(
                    $query->getQuery(),
                    $request->query->getInt('page', 1),
                    $perPage
                );
                
                return $this->render('home/index.html.twig', [
                    'personne' => $personne,
                ]);
            }
        } 
        else if ($user->getRoles() == ["ROLE_USERS"]) {
            if ($user->getActive() == "False") {
                $this->addFlash('error', "Votre compte n'est pas encore activé");
                return $this->redirectToRoute('app_login');
            } else {
                $conge = $this->getDoctrine()->getManager();
                $type = $conge->getRepository(Conge::class)->findByExampleField($user->getId());
                $notification = $NotificationRepository->findbyemploye($user->getId(), 0);
    
                return $this->render('conge/index.html.twig', [
                    'cong' => $type,
                    'notification' => $notification,
                ]);         
            }
        }
    }
  /**
     * @Route("/show/{id}",name="show")
     */
    public function show(\Symfony\Component\HttpFoundation\Request $request ,$id): Response
    {
        $employe=$this->getDoctrine()->getRepository(Rh::class)->find($id);
       
        
    return $this->render('home/details.html.twig',
    [
        'Rh'=>$employe
    ]);
}
#[Route('/updateRh/{id}', name: 'app_rh_update')]  // Nom de route plus standard
public function update(Request $request, Rh $rh, EntityManagerInterface $entityManager): Response
{
    if (!$this->isGranted('ROLE_Rh')) {
        $this->addFlash('error', "Accès refusé");
        return $this->redirectToRoute('app_home');
    }

    $form = $this->createForm(RegistrationFormMType::class, $rh);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', "Mise à jour réussie");
        return $this->redirectToRoute('app_home');
    }

    return $this->render('home/modifier.html.twig', [
        'for' => $form->createView(),
    ]);
}
#[Route('/deleteRH/{id}', name: 'app_rh_delete')]
public function refuser($id, Request $request, EntityManagerInterface $manager): Response
{    $repo=$this->getDoctrine()->getRepository(Rh::class );
    $employe=$repo->find($id);
    $users=$this->getDoctrine()->getManager();
    $users->remove($employe);
    $users->flush();
    return $this->redirectToRoute('app_home');
}
}

