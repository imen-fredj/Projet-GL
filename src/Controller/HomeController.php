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

use App\Factory\UserFactory;
class HomeController extends AbstractController
{#[Route('/home', name: 'app_home')]
    public function index(
        Request $request, 
        RhRepository $rhRepository, 
        PaginatorInterface $paginator, 
        
        UserFactory $userFactory
    ): Response {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        switch ($user->getRoles()[0]) {
            case 'ROLE_ADMIN':
                return $this->render('homeadmin/index.html.twig', []);
                
            case 'ROLE_Rh':
                if (($user->getActive()) !== 'True') {
                    $this->addFlash('error', "Votre compte n'est pas encore activé");
                    return $this->redirectToRoute('app_login');
                }
                
               
                
               
                
                return $this->render('homeresponsable/index.html.twig');
                
            case 'ROLE_USERS':
                if (($user->getActive()) !== 'True') {
                    $this->addFlash('error', "Votre compte n'est pas encore activé");
                    return $this->redirectToRoute('app_login');
                }
                
                
             
    
                return $this->render('homeempl/index.html.twig'
                    
                   );
                
           
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
#[Route('/updateRh/{id}', name: 'app_rh_update')]
public function update(
    Request $request, 
    Rh $rh, 
    EntityManagerInterface $entityManager,
    UserFactory $userFactory
): Response {
    $form = $this->createForm(RegistrationFormMType::class, $rh);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupération du mot de passe si modifié
        $plainPassword = $form->has('plainPassword') ? $form->get('plainPassword')->getData() : null;

        // Mise à jour via UserFactory
        $updatedUser = $userFactory->updateUser(
            $rh,
            [
                'email' => $rh->getEmail(),
                'plainPassword' => $plainPassword,
                'nom' => $rh->getNom(),
                'prenom' => $rh->getPrenom(),
                'adresse' => $rh->getAdresse(),
                'cin' => $rh->getCin(),
                'telephone' => $rh->getTelephone(),
                'image' => $rh->getImage(),
                'active' => $rh->getActive()
            ]
        );

        $entityManager->flush();
        $this->addFlash('success', "Mise à jour réussie");
        return $this->redirectToRoute('app_home');
    }

    return $this->render('home/modifier.html.twig', [
        'form' => $form->createView(),
        'rh' => $rh
    ]);
}
#[Route('/deleteRH/{id}', name: 'app_rh_delete')]
public function deleteRH(
    $id, 
    Request $request, 
    EntityManagerInterface $manager,
    UserFactory $userFactory
): Response {
    // if (!$this->isGranted('ROLE_Rh')) {
    //     $this->addFlash('error', "Accès refusé");
    //     return $this->redirectToRoute('app_home');
    // }

    $employe = $manager->getRepository(Rh::class)->find($id);
    
    if (!$employe) {
        $this->addFlash('error', "Utilisateur non trouvé");
        return $this->redirectToRoute('app_home');
    }
    $manager->remove($employe);
    $manager->flush();
    
    $this->addFlash('success', "Utilisateur supprimé avec succès");
    return $this->redirectToRoute('app_home');
}
}


