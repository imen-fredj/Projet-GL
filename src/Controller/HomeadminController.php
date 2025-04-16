<?php

namespace App\Controller;

use App\Entity\Rh;
use App\Repository\RhRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeadminController extends AbstractController
{
    #[Route('/homeadmin', name: 'app_homeadmin')]
    public function index(RhRepository $rhRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        
        return $this->render('homeadmin/index.html.twig', [
            'current_user' => $user
        ]);
    }
}