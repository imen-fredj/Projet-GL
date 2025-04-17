<?php

namespace App\Controller;
use App\Form\SalaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvanceSalaireRepository;
use App\Entity\AvanceSalaire;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
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

    #[Route('/listavance', name: 'app_listavance')]
    public function indexAvance(AvanceSalaireRepository $AvanceSalaireRepository): Response
    {
        $avances = $AvanceSalaireRepository->findAll();
        return $this->render('avance_salaire/indexRh.html.twig', [
            'avances' => $avances,
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
 /**
 * @Route("/accepte_avance/{id}", name="avance_accepte")
 */
public function accepteravance($id, EntityManagerInterface $manager): Response
{
    $avance = $this->getDoctrine()->getRepository(AvanceSalaire::class)->find($id);

    if ($avance && $avance->getEtat() == 'en cours') {
        $avance->setEtat('Confirmer');
        $notification = new Notification();
  
        // Vérifiez qui est le demandeur
        $employeDemandeur = $avance->getRh();
        $notification->setRecepteur($employeDemandeur);
        $responsable = $this->getUser();

        
       
        $notification->setText("Demande d'avance sur salaire a été acceptée.");
        $notification->setDateNotification(new \DateTime());
       
        $notification->setDestinateur($responsable);
        $notification->setIsRead(0);

        $manager->persist($avance);
        $manager->persist($notification);
        $manager->flush();
    }

    return $this->redirectToRoute('app_listavance');
}
/**
 * @Route("/refuse_avance/{id}", name="avance_refuse")
 */
public function refuseravance($id, EntityManagerInterface $manager): Response
{
    // Trouver l'avance par son ID
    $avance = $this->getDoctrine()->getRepository(AvanceSalaire::class)->find($id);

    // Si l'avance existe et que son état est "en cours", on la marque comme refusée
    if ($avance && $avance->getEtat() == 'en cours') {
        $avance->setEtat('rejeter');

       
        $manager->persist($avance);
        $manager->flush();
    }

    // Redirection après le refus
    return $this->redirectToRoute('app_listavance');
}
}
