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
        
        // Compter uniquement les utilisateurs avec ROLE_Rh
        $totalRhUsers = $rhRepository->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.roles LIKE :role')
            ->setParameter('role', '%ROLE_Rh%')
            ->getQuery()
            ->getSingleScalarResult();
        
        // RH Actifs (avec ROLE_Rh et active = '1')
        $activeRh = $rhRepository->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.active = :active')
            ->andWhere('r.roles LIKE :role')
            ->setParameter('active', 'True')
            ->setParameter('role', '%ROLE_Rh%')
            ->getQuery()
            ->getSingleScalarResult();
            
        // Demandes en attente (avec ROLE_Rh et active = '0')
        $pendingRequests = $rhRepository->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.active = :active')
            ->andWhere('r.roles LIKE :role')
            ->setParameter('active', 'False')
            ->setParameter('role', '%ROLE_Rh%')
            ->getQuery()
            ->getSingleScalarResult();
        
        // Derniers RH inscrits (5 derniers)
        $recentRhUsers = $rhRepository->createQueryBuilder('r')
            ->where('r.roles LIKE :role')
            ->setParameter('role', '%ROLE_Rh%')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
        
        // Activités récentes
        $recentActivities = [
            [
                'title' => 'Nouveau RH inscrit',
                'description' => 'Un nouveau responsable RH a créé un compte',
                'date' => new \DateTime('-1 hour'),
                'user' => 'System'
            ],
            [
                'title' => 'Validation de compte RH',
                'description' => 'Un compte RH a été validé',
                'date' => new \DateTime('-3 hours'),
                'user' => $user->getNom()
            ]
        ];
        
        return $this->render('homeadmin/index.html.twig', [
            'total_users' => $totalRhUsers,
            'active_rh' => $activeRh,
            'pending_requests' => $pendingRequests,
            'recent_rh_users' => $recentRhUsers,
            'recent_activities' => $recentActivities,
            'current_user' => $user
        ]);
    }
}