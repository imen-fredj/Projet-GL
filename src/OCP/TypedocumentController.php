<?php

namespace App\Controller;

use App\Entity\Typedocument;
use App\Form\DocumentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/typedocument')]
class TypedocumentController extends AbstractController
{
    #[Route('/', name: 'app_typedocument')]
    public function index(EntityManagerInterface $em): Response
    {
        $items = $em->getRepository(Typedocument::class)->findAll();
        return $this->render('typedocument/index.html.twig', [
            'type' => $items
        ]);
    }

    #[Route('/ajout', name: 'app_ajoute_document')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $entity = new Typedocument();
        $form = $this->createForm(DocumentType::class, $entity);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('app_typedocument');
        }

        return $this->render('typedocument/ajoute.html.twig', [
            'for' => $form->createView()
        ]);
    }

    #[Route('/supprimer/{id}', name: 'app_supprimedocument')]
    public function delete($id, EntityManagerInterface $em): Response
    {
        $entity = $em->getRepository(Typedocument::class)->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Document type not found');
        }

        $em->remove($entity);
        $em->flush();
        $this->addFlash('success', 'Type de document supprimé avec succès');

        return $this->redirectToRoute('app_typedocument');
    }
}
