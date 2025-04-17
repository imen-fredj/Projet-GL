<?php

namespace App\Controller;

use App\Entity\TypeModification;
use App\Form\ModificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypemodificationController extends AbstractController
{
    #[Route('/typemodification', name: 'app_typemodification')]
    public function index(EntityManagerInterface $em): Response
    {
        $types = $em->getRepository(TypeModification::class)->findAll();
        return $this->render('typemodification/index.html.twig', [
            'types' => $types
        ]);
    }

    #[Route('/typemodification/ajout', name: 'app_ajoute_modification')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $type = new TypeModification();
        $form = $this->createForm(ModificationType::class, $type);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);
            $em->flush();
            return $this->redirectToRoute('app_typemodification');
        }

        return $this->render('typemodification/ajoute.html.twig', [
            'for' => $form->createView()
        ]);
    }

    #[Route('/supprimemodification/{id}', name: 'app_supprimemodification')]
    public function delete($id, EntityManagerInterface $em): Response
    {
        $type = $em->getRepository(TypeModification::class)->find($id);
        
        if (!$type) {
            throw $this->createNotFoundException('Type de modification non trouvé');
        }

        $em->remove($type);
        $em->flush();
        $this->addFlash('success', 'Type de modification supprimé avec succès');

        return $this->redirectToRoute('app_typemodification');
    }
}