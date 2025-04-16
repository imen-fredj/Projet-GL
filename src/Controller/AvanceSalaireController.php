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
use App\Controller\AbstractDemandeController;
use App\Entity\Notification;  // Add this line with other use statements

class AvanceSalaireController extends AbstractDemandeController
{
    #[Route('/avance/salaire', name: 'app_avance_salaire')]
    public function index(AvanceSalaireRepository $repository): Response
    {
        return $this->handleIndex('findByExampleField');
    }
    
    protected function getIndexTemplate(): string
    {
        return 'avance_salaire/index.html.twig';
    }
    

    protected function getEntityContextName(): string
    {
        return 'salaire';
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
    public function ajouter(Request $request): Response
    {
        return $this->handleAdd($request, 'app_avance_salaire');
    }
    
    protected function createNewEntity()
    {
        return new AvanceSalaire();
    }
    
    protected function getFormType(): string
    {
        return SalaireType::class;
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
//     public function modifier(\Symfony\Component\HttpFoundation\Request $request ,$id): Response
//     {
//         $salaire=$this->getDoctrine()->getRepository(AvanceSalaire::class)->find($id);
      
//         $form=$this->createForm(SalaireType::class,$salaire);
        
//         $form->handleRequest($request);
//         if($form->isSubmitted() )
//         {
           
//             $em=$this->getDoctrine()->getManager();
          
//             $em->persist($salaire);
//             $em->flush();
//             return $this->redirectToRoute("app_avance_salaire");
//     }
//     return $this->render('avance_salaire/demande.html.twig',
//     [
//         'for'=>$form->createView()
//     ]);
// }

public function modifier(Request $request, $id): Response
{
    return $this->handleEdit($request, $id, 'app_avance_salaire');
}

protected function getFormTemplate(): string
{
    return 'avance_salaire/demande.html.twig';
}

#[Route('/avance_accepte/{id}', name: 'avance_accepte')]
public function accepteravance($id, EntityManagerInterface $manager): Response
{
    return $this->handleAccept(
        $id,
        $manager,
        AvanceSalaire::class,
        'Confirmer',
        'app_listavance'
    );
}

protected function updateEntityState($entity, string $successState): void
{
    if ($entity->getEtat() == 'en cours') {
        $entity->setEtat($successState);
    }
}

protected function createAcceptNotification($entity, EntityManagerInterface $manager): void
{
    $notification = new Notification();
    $notification->setText("Demande d'avance sur salaire a été acceptée.");
    $notification->setRecepteur($entity->getRh());
    $notification->setDestinateur($this->getUser());
    $notification->setDateNotification(new \DateTime());
    $notification->setIsRead(0);
    
    $manager->persist($notification);
}
/**
 * @Route("/refuse_avance/{id}", name="avance_refuse")
 */
public function refuseravance($id, EntityManagerInterface $manager): Response
{
    return $this->handleReject(
        $id,
        $manager,
        AvanceSalaire::class,
        'rejeter',
        'app_listavance'
    );
}

protected function updateEntityOnReject($entity, string $rejectedState, EntityManagerInterface $manager): void
{
    if ($entity->getEtat() == 'en cours') {
        $entity->setEtat($rejectedState);
        $manager->persist($entity);
    }
}

protected function createRejectionNotification($entity, EntityManagerInterface $manager): void
{
}

protected function getEntityClass(): string
{
    return AvanceSalaire::class;
}
protected function preEditPersist($entity, EntityManagerInterface $em): void
{

}

}
