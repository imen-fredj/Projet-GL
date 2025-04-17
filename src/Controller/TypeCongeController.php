<?php
// src/Controller/TypeCongeController.php
namespace App\Controller;

use App\Entity\Typeconge;
use App\Form\CongeformType;
use App\Repository\TypecongeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TypeCongeController extends AbstractController
{
    public function __construct(
        private TypecongeRepositoryInterface $typecongeRepository
    ) {
    }

    #[Route('/typeconge', name: 'app_type_conge')]
    public function index(): Response
    {
        $type = $this->typecongeRepository->findAll();
        
        return $this->render('type_conge/index.html.twig', [
            'type' => $type
        ]);
    }

    #[Route('/ajoute', name: 'app_ajoute_conge')]
    public function ajoute(Request $request): Response
    {
        $type = new Typeconge();
        $form = $this->createForm(CongeformType::class, $type);
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->typecongeRepository->save($type, true);
            return $this->redirectToRoute('app_type_conge');
        }
        
        return $this->render('type_conge/ajoute.html.twig', [
            'for' => $form->createView()
        ]);
    }

    #[Route("/supprimetype/{id}", name: "app_supprimetype")]
    public function supprimer(int $id): RedirectResponse
    {    
        $type = $this->typecongeRepository->find($id);
        
        if (!$type) {
            throw $this->createNotFoundException('Type de congé non trouvé');
        }

        if ($this->typecongeRepository->countRelatedConges($type) > 0) {
            $this->addFlash(
                'error', 
                'Vous ne pouvez pas supprimer ce type de congé car il est utilisé par des demandes existantes.'
            );
            return $this->redirectToRoute('app_type_conge');
        }

        try {
            $this->typecongeRepository->remove($type, true);
            $this->addFlash('success', 'Type de congé supprimé avec succès');
        } catch (\Exception $e) {
            $this->addFlash(
                'error', 
                'Erreur lors de la suppression : ' . $e->getMessage()
            );
        }

        return $this->redirectToRoute('app_type_conge');
    }
}