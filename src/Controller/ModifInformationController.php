<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ModificationInformationRepository;
use App\Entity\ModificationInformation;
use App\Form\ModificationInformationType;
class ModifInformationController extends AbstractController
{
    #[Route('/modif/information', name: 'app_modif_information')]
    public function index(): Response
    { $user = $this->getUser();
        $info= $this->getDoctrine()->getManager();
        $modification= $info->getRepository(ModificationInformation::class)->findByExampleField($user->getId());
        return $this->render('modif_information/index.html.twig', [
            'information' => $modification,
        ]);
    }
   
    #[Route('/supprime_info/{id}', name: 'app_supprime_information')]
    function supprimer_info($id):Response
    {
        $repo=$this->getDoctrine()->getRepository(ModificationInformation::class );
        $info=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($info);
        $em->flush();
        return $this->redirectToRoute('app_modif_information');
    }
     /**
     * @Route("/modifier_informatione/{id}",name="app_modifier_information")
     */
    public function modifier(\Symfony\Component\HttpFoundation\Request $request ,$id): Response
    {
        $info=$this->getDoctrine()->getRepository(ModificationInformation::class)->find($id);
      
        $form=$this->createForm(ModificationInformationType::class,$info);
        
        $form->handleRequest($request);
        if($form->isSubmitted() )
        {
           
            $em=$this->getDoctrine()->getManager();
          
            $em->persist($info);
            $em->flush();
            return $this->redirectToRoute("app_modif_information");
    }
    return $this->render('modif_information/demande.html.twig', [
        'for'=>$form->createView(),

    ]);
    }
    /**
     * @Route("/ajoutemodification", name="app_ajoutemodification")
     */
    public function ajouter(\Symfony\Component\HttpFoundation\Request $request): Response
    {   
          $modification=new ModificationInformation();
            $modification->setDatedemande(new \DateTime());
            $modification->setEtat('en cours');
             $modification->setRh($user = $this->getUser());
            $form=$this->createForm(ModificationInformationType::class,$modification);
           
            $form->handleRequest($request); 
            if($form->isSubmitted())
            {
                $modification_info=$this->getDoctrine()->getManager();
                $modification_info->persist($modification);
                $modification_info->flush();
                return $this->redirectToRoute('app_modif_information');
            }
            return $this->render('modif_information/demande.html.twig', [
                'for'=>$form->createView(),
  
            ]);
        }
}
