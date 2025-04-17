<?php

namespace App\Controller;

use App\Entity\Typeconge;
use App\Form\CongeformType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeCongeController extends AbstractController
{
    private $entityClass = Typeconge::class;
    private $formTypeClass = CongeformType::class;
    private $indexRoute = 'app_type_conge';
    private $indexTemplate = 'type_conge/index.html.twig';
    private $addEditTemplate = 'type_conge/ajoute.html.twig';

    #[Route('/typeconge', name: 'app_type_conge')]
    public function index(EntityManagerInterface $em): Response
    {
        $type = $em->getRepository($this->entityClass)->findAll(); // Changé $items en $type
        return $this->render($this->indexTemplate, [
            'type' => $type // Correspond au nom utilisé dans le template
        ]);
    }

   #[Route('/ajoute', name: 'app_ajoute_conge')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $entity = new $this->entityClass();
        $form = $this->createForm($this->formTypeClass, $entity);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute($this->indexRoute);
        }

        return $this->render($this->addEditTemplate, [
            'form' => $form->createView() // Changé 'for' en 'form'
        ]);
    }

    #[Route('/supprimetype/{id}', name: 'app_supprimetype')]
    public function delete($id, EntityManagerInterface $em): Response
    {
        $entity = $em->getRepository($this->entityClass)->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Type de congé non trouvé');
        }

        if (method_exists($entity, 'getConges') && $entity->getConges()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer ce type car il est utilisé');
            return $this->redirectToRoute($this->indexRoute);
        }

        $em->remove($entity);
        $em->flush();
        $this->addFlash('success', 'Suppression réussie');

        return $this->redirectToRoute($this->indexRoute);
    }
}