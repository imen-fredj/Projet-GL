<?php

namespace App\Controller;
use App\Entity\TypeModification;
use App\Form\ModificationType;
use App\Repository\TypeModificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class TypemodificationController extends AbstractController
{
    #[Route('/typemodification', name: 'app_typemodification')]
    public function index(TypeModificationRepository $TypeModificationRepository): Response
    {
        $type_modifcation= $this->getDoctrine()->getManager();
        $type= $type_modifcation->getRepository(TypeModification::class)->findAll();
        return $this->render('typemodification/index.html.twig', [
            'type' => $type
        ]);
    }
    #[Route('/ajout', name: 'app_ajoute_modification')]
    public function ajoute(\Symfony\Component\HttpFoundation\Request $request): Response
    {$type=new TypeModification();
        $form=$this->createForm(ModificationType::class,$type);
      
        $form->handleRequest($request); 
        if($form->isSubmitted())
        {
            $type_modif=$this->getDoctrine()->getManager();
            $type_modif->persist($type);
            $type_modif->flush();
            return $this->redirectToRoute('app_typemodification');
        }
        return $this->render('typemodification/ajoute.html.twig', [
            'for'=>$form->createView()
        ]);
    }

    /**
 * @Route("/supprimemodification/{id}", name="app_supprimemodification")
 */
public function supprimer($id, EntityManagerInterface $em): Response
{
    $type = $em->getRepository(TypeModification::class)->find($id);
    
    if (!$type) {
        throw $this->createNotFoundException('Type de modification non trouvé');
    }

    // Vérification des relations avant suppression
    // Adaptez cette partie selon vos relations d'entités
    // Exemple si vous avez une relation avec une entité Modification:
    // if ($type->getModifications()->count() > 0) {
    //     $this->addFlash(
    //         'error', 
    //         'Vous ne pouvez pas supprimer ce type car il est utilisé par des modifications existantes.'
    //     );
    //     return $this->redirectToRoute('app_typemodification');
    // }

    try {
        $em->remove($type);
        $em->flush();
        $this->addFlash('success', 'Type de modification supprimé avec succès');
    } catch (\Exception $e) {
        $this->addFlash(
            'error', 
            'Vous ne pouvez pas supprimer ce type de congé car il est utilisé par des demandes existantes.'
        );
    }

    return $this->redirectToRoute('app_typemodification');
}

}
