<?php

namespace App\Controller;
use App\Form\SalaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvanceSalaireRepository;
use App\Entity\AvanceSalaire;
use Symfony\Component\HttpFoundation\Request; 
class AvanceSalaireController extends AbstractController
{
    #[Route('/avance/salaire', name: 'app_avance_salaire')]
    public function index(AvanceSalaireRepository $AvanceSalaireRepository): Response
    { $user = $this->getUser();
        $salaire= $this->getDoctrine()->getManager();
        $avance= $salaire->getRepository(AvanceSalaire::class)->findByExampleField($user->getId());
       
        return $this->render('avance_salaire/index.html.twig', [
            'salaire' => $avance,
        ]);
    }
     /**
     * @Route("/ajouteavance", name="app_avance")
     */
    public function ajouter(\Symfony\Component\HttpFoundation\Request $request): Response
    {   
          $avance=new AvanceSalaire();
            $avance->setDatedemande(new \DateTime());
            $avance->setEtat('en cours');
             $avance->setRh($user = $this->getUser());
            $form=$this->createForm(SalaireType::class,$avance);
           
            $form->handleRequest($request); 
            if($form->isSubmitted())
            {
                $avance_salaiare=$this->getDoctrine()->getManager();
                $avance_salaiare->persist($avance);
                $avance_salaiare->flush();
                return $this->redirectToRoute('app_avance_salaire');
            }
            return $this->render('avance_salaire/demande.html.twig', [
                'for'=>$form->createView(),
  
            ]);
        }
        /**
     * @Route("/supprimeravance/{id}",name="app_supprimer")
     
     */
    public function refuser($id, Request $request, EntityManagerInterface $manager): Response
    {    $repo=$this->getDoctrine()->getRepository(AvanceSalaire::class );
        $salaire=$repo->find($id);
        $avance=$this->getDoctrine()->getManager();
        $avance->remove($salaire);
        $avance->flush();
        return $this->redirectToRoute('app_avance_salaire');
}

 /**
     * @Route("/modifavance/{id}",name="app_modifavance")
     */
    public function modifier(\Symfony\Component\HttpFoundation\Request $request ,$id): Response
    {
        $salaire=$this->getDoctrine()->getRepository(AvanceSalaire::class)->find($id);
      
        $form=$this->createForm(SalaireType::class,$salaire);
        
        $form->handleRequest($request);
        if($form->isSubmitted() )
        {
           
            $em=$this->getDoctrine()->getManager();
          
            $em->persist($salaire);
            $em->flush();
            return $this->redirectToRoute("app_avance_salaire");
    }
    return $this->render('avance_salaire/demande.html.twig',
    [
        'for'=>$form->createView()
    ]);
}
}
