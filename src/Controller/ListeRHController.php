<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Rh;
use Symfony\Component\HttpFoundation\Request; 
class ListeRHController extends AbstractController
{
    #[Route('/liste/r/h', name: 'app_liste_r_h')]
    public function index(): Response
    {  $emplo= $this->getDoctrine()->getManager();
        $employe= $emplo->getRepository(Rh::class)->findBy(['active'=>'True']);
       
            return $this->render('liste_rh/index.html.twig', [
                'personne' => $employe,
               
            ]);
            
      
    }
    /**
     * 
     * @Route("/supprimer/{id}" , name="app_supprime")
     */

    function supprimer($id):Response
    {
        $repo=$this->getDoctrine()->getRepository(Rh::class );
        $employe=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($employe);
        $em->flush();
        return $this->redirectToRoute('app_liste_r_h');
    }
}
