<?php

namespace App\Controller;

use App\Entity\ModificationInformation;
use App\Form\ModificationInformationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifInformationController extends AbstractDemandeController
{
    #[Route('/modif/information', name: 'app_modif_information')]
    public function index(): Response
    {
        return $this->handleIndex('findByExampleField');
    }
    
    protected function getIndexTemplate(): string
    {
        return 'modif_information/index.html.twig';
    }
    
    protected function getEntityContextName(): string
    {
        return 'information';
    }
   
    #[Route('/supprime_info/{id}', name: 'app_supprime_information')]
    public function supprimer_info($id, EntityManagerInterface $em): Response
    {
        return $this->handleDelete($id, $em, 'app_modif_information');
    }

    #[Route("/modifier_informatione/{id}", name: "app_modifier_information")]
    public function modifier(Request $request, $id): Response
    {
        return $this->handleEdit($request, $id, 'app_modif_information');
    }
    
    protected function getFormTemplate(): string
    {
        return 'modif_information/demande.html.twig';
    }

    #[Route("/ajoutemodification", name: "app_ajoutemodification")]
    public function ajouter(Request $request): Response
    {
        return $this->handleAdd($request, 'app_modif_information');
    }

    // Required abstract method implementations
    protected function createNewEntity()
    {
        return new ModificationInformation();
    }

    protected function getFormType(): string
    {
        return ModificationInformationType::class;
    }

    protected function getEntityClass(): string
    {
        return ModificationInformation::class;
    }

    // In ModifInformationController.php
protected function preEditPersist($entity, EntityManagerInterface $em): void
{
}

    // Unused but required methods (for acceptance/rejection flows)
    protected function updateEntityState($entity, string $successState): void {}
    protected function createAcceptNotification($entity, EntityManagerInterface $em): void {}
    protected function updateEntityOnReject($entity, string $rejectedState, EntityManagerInterface $em): void {}
    protected function createRejectionNotification($entity, EntityManagerInterface $em): void {}
}