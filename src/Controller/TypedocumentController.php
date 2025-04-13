<?php

namespace App\Controller;
use App\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Typedocument;
class TypedocumentController extends AbstractController
{
    #[Route('/typedocument', name: 'app_typedocument')]
    public function index(): Response
    {
        $type_document= $this->getDoctrine()->getManager();
        $type= $type_document->getRepository(Typedocument::class)->findAll();
        return $this->render('typedocument/index.html.twig', [
            'type' => $type
        ]);
       
    }
    #[Route('/ajoutdoc', name: 'app_ajoute_document')]
    public function ajout(\Symfony\Component\HttpFoundation\Request $request): Response
    {$type=new Typedocument();
        $form=$this->createForm(DocumentType::class,$type);
      
        $form->handleRequest($request); 
        if($form->isSubmitted())
        {
            $type_do=$this->getDoctrine()->getManager();
            $type_do->persist($type);
            $type_do->flush();
            return $this->redirectToRoute('app_typedocument');
        }
        return $this->render('typedocument/ajoute.html.twig', [
            'for'=>$form->createView()
        ]);
    }
    /**
     * 
     * @Route("/supprimetypedocument/{id}" , name="app_supprimedocument")
     */

    function supprimer($id):Response
    {
        $repo=$this->getDoctrine()->getRepository(Typedocument::class );
        $type=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($type);
        $em->flush();
        return $this->redirectToRoute('app_typedocument');
        
    }
}
