<?php

namespace App\Controller;
use App\Entity\Rh;
use App\Repository\RhRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 

class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    // public function index(RhRepository $RhRepository): Response
    // {
    //     $emplo= $this->getDoctrine()->getManager();
    //  $employe= $emplo->getRepository(Rh::class)->findBy(['active'=>'False'],['nom' => 'asc']);
    //  $user = $this->getUser();
    //  if ($user->getRoles() == ["ROLE_ADMIN"] ){
    //     return $this->render('compte/indexrh.html.twig', [
    //         'personne' => $employe
               
    //     ]);
    //  }else{
    //     return $this->render('compte/index.html.twig', [
    //         'personne' => $employe
               
    //     ]);
    // }}

    public function index(RhRepository $rhRepository): Response
    {
        $user = $this->getUser();
        $pendingAccounts = $rhRepository->findPendingAccounts(); // Uses repository method

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->render('compte/indexrh.html.twig', [
                'personne' => $pendingAccounts
            ]);
        }

        return $this->render('compte/index.html.twig', [
            'personne' => $pendingAccounts
        ]);
    }

     /**
     * @Route("/accepter/{id}",name="accepter")
     
     */
    public function accepter($id, Request $request, EntityManagerInterface $manager): Response
    {   $empl=$this->getDoctrine()->getRepository(Rh::class)->find($id);
        $empl->setActive('True');
        $manager->persist($empl);
        $manager->flush();
        return $this->redirectToRoute('app_compte');
}
  /**
     * @Route("/refuser/{id}",name="refuser")
     
     */
    public function refuser($id, Request $request, EntityManagerInterface $manager): Response
    {    $repo=$this->getDoctrine()->getRepository(Rh::class );
        $employe=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($employe);
        $em->flush();
        return $this->redirectToRoute('app_compte');
}
}
