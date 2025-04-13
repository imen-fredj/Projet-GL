<?php

namespace App\Controller;
use App\Entity\Typeconge;
use App\Entity\Conge;
use App\Form\CongeformType;
use App\Repository\TypecongeRepository;
use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class TypeCongeController extends AbstractController
{
    #[Route('/typeconge', name: 'app_type_conge')]
    public function index(TypecongeRepository $TypecongeRepository): Response
    {$type_conge= $this->getDoctrine()->getManager();
        $type= $type_conge->getRepository(Typeconge::class)->findAll();
        return $this->render('type_conge/index.html.twig', [
            'type' => $type
        ]);
    }
    #[Route('/ajoute', name: 'app_ajoute_conge')]
    public function ajoute(\Symfony\Component\HttpFoundation\Request $request): Response
    {$type=new Typeconge();
        $form=$this->createForm(CongeformType::class,$type);
      
        $form->handleRequest($request); 
        if($form->isSubmitted())
        {
            $type_co=$this->getDoctrine()->getManager();
            $type_co->persist($type);
            $type_co->flush();
            return $this->redirectToRoute('app_type_conge');
        }
        return $this->render('type_conge/ajoute.html.twig', [
            'for'=>$form->createView()
        ]);
    }
   

/**
 * @Route("/supprimetype/{id}", name="app_supprimetype")
 */
public function supprimer($id, EntityManagerInterface $em): Response
{    
    $type = $em->getRepository(Typeconge::class)->find($id);
    
    if (!$type) {
        throw $this->createNotFoundException('Type de congé non trouvé');
    }

    // Check if there are any related Conge entities
    if ($type->getConges()->count() > 0) {
        $this->addFlash(
            'error', 
            'Vous ne pouvez pas supprimer ce type de congé car il est utilisé par des demandes existantes.'
        );
        return $this->redirectToRoute('app_type_conge');
    }

    try {
        $em->remove($type);
        $em->flush();
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
