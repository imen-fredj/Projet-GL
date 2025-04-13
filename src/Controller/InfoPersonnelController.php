<?php

namespace App\Controller;
use App\Entity\Rh;
use App\Form\ModificationProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InfoPersonnelController extends AbstractController
{
    #[Route('/info/personnel', name: 'app_info_personnel')]
    public function index(EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();
       
        
        $employe = $entityManager->getRepository(Rh::class)->findByExampleField($user->getId());
        
        return $this->render('info_personnel/index.html.twig', [
            'personne' => $employe
        ]);
    }
   
    #[Route('/info/personnel/edit/{id}', name: 'app_info_personnel_edit')]
public function edit(Request $request, EntityManagerInterface $entityManager, Rh $rh, UserPasswordHasherInterface $passwordHasher): Response
{
    $rh = $this->getUser();
    
   
    $form = $this->createForm(ModificationProfilType::class, $rh);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        if ($form->isValid()) {
            // Gestion du mot de passe
            $password = $form->get('password')->get('first')->getData();
            if ($password) {
                $rh->setPassword(
                    $passwordHasher->hashPassword($rh, $password)
                );
            }

            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour!');
            return $this->redirectToRoute('app_info_personnel');
        } else {
            $this->addFlash('error', 'Des erreurs sont présentes dans le formulaire.');
        }
    }

    return $this->render('info_personnel/edit.html.twig', [
        'form' => $form->createView(),
        'rh' => $rh
    ]);
}
#[Route('/update-profile-image/{id}', name: 'update_profile_image')]
public function updateProfileImage(Request $request, Rh $rh, EntityManagerInterface $entityManager): Response
{
    
    $uploadedFile = $request->files->get('profile_image');

    if ($uploadedFile) {
        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
        
        // Move the file to the directory where images are stored
        $uploadedFile->move(
            $this->getParameter('images_directory'), // Define this in services.yaml
            $newFilename
        );
        
        // Update the image property of the Rh entity
        $rh->setImage($newFilename);
        $entityManager->flush();
        
        $this->addFlash('success', 'Photo de profil mise à jour avec succès!');
    }
    
    return $this->redirectToRoute('app_info_personnel');
}
}
