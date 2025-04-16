<?php

namespace App\Controller;


use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Factory\UserFactory;
class RegistrationController extends AbstractController
{#[Route('/registration/{type}', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        string $type,
        Request $request,
        EntityManagerInterface $em,
        UserFactory $userFactory,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $validTypes = ['admin', 'rh', 'user'];
        if (!in_array($type, $validTypes)) {
            throw $this->createNotFoundException('Type d\'utilisateur non valide');
        }
    
        $formOptions = [
            'require_prenom' => in_array($type, ['admin', 'rh']),
            'validation_groups' => ['Default', $type]
        ];
        
        $form = $this->createForm(RegistrationFormType::class, null, $formOptions);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();
                $data = [
                    'nom' => $formData->getNom(),
                    'prenom' => $formData->getPrenom(),
                    'cin' => $formData->getCin(),
                    'adresse' => $formData->getAdresse(),
                    'telephone' => $formData->getTelephone()
                ];
    
                // Gestion de l'image
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $data['image'] = $newFilename;
                }
    
                $user = $userFactory->create(
                    $type,
                    $formData->getEmail(),
                    $formData->getPassword(),
                    $data
                );
    
                $em->persist($user);
                $em->flush();
    
                $this->addFlash('success', 'Inscription réussie !');
                return $this->redirectToRoute('app_login');
                
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'inscription');
               
            }
        }
    
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'type' => $type
        ]);
    }
//     #[Route('/register', name: 'app_register')]
//     public function registeration(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
//     {
//         $user = new Rh();
//         $form = $this->createForm(RegistrationFormType::class, $user);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $file = $form->get('image')->getData();
//             $fileName = md5(uniqid()).'.'.$file->guessExtension();
           
//             $file->move($this->getParameter('images_directory'),$fileName);
//             $user->setImage($fileName);

//                 $user->setActive('False');
        
//             $user->setPassword(
//                 $userPasswordHasher->hashPassword(
//                     $user,
//                     $form->get('password')->getData()
//                 )
//             );
//             if( $user->getActive() == "False"){
//                 $this->addFlash('error',"Il faut attendé l'activation du votre compte");
           
//                 $entityManager->persist($user);
//             $entityManager->flush();
//             $this->addFlash('notice',"vous avez une nouvelle demande de compte");

//             return $this->redirectToRoute('app_login');
//             }
//         }

//         return $this->render('registration/register.html.twig', [
//             'registrationForm' => $form->createView(),
//         ]);
//     }
 }
