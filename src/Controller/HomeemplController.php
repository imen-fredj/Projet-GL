<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class HomeemplController extends AbstractController
{
    #[Route('/homeempl', name: 'app_homeempl')]
    public function index(): Response
    {   $user = $this->getUser();
    
            
        return $this->render('homeempl\index.html.twig');
     
    }
    #[Route('/emp', name: 'app_empl')]
    public function count(EntityManagerInterface $manager): Response
    {   $user = $this->getUser();
       
     
       
        return $this->render('baseEmp.html.twig');
    
    }
}
