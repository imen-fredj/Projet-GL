<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractDemandeController extends AbstractController
{
    //////////////////// Template Method pour l'affichage index//////////////////////////
    final protected function handleIndex(
        string $repositoryMethod = 'findByExampleField',
        array $repositoryParams = []
    ): Response {
        $user = $this->getUser();
        $entities = $this->getEntities($repositoryMethod, array_merge([$user->getId()], $repositoryParams));
        
        return $this->render($this->getIndexTemplate(), [
            $this->getEntityContextName() => $entities
        ]);
    }
    
    // Méthodes abstraites
    abstract protected function getIndexTemplate(): string;
    abstract protected function getEntityContextName(): string;
    
    // Méthode avec implémentation par défaut
    protected function getEntities(string $method, array $params = [])
    {
        return $this->getDoctrine()
            ->getRepository($this->getEntityClass())
            ->$method(...$params);
    }

    ///////////////////// Template Method pour l'acceptation //////////////////////////

        final protected function handleAccept(
            $id,
            EntityManagerInterface $manager,
            string $entityClass,
            string $successState,
            string $redirectRoute
        ): Response {
            // 1. Récupération de l'entité
            $entity = $this->getDoctrine()->getRepository($entityClass)->find($id);
            
            // 2. Validation et mise à jour
            if ($entity) {
                $this->updateEntityState($entity, $successState);
                $manager->persist($entity);
                
                // 3. Notification si nécessaire
                $this->createAcceptNotification($entity, $manager);
                
                $manager->flush();
            }
            
            return $this->redirectToRoute($redirectRoute);
        }
        
        // Méthodes à implémenter
        abstract protected function updateEntityState($entity, string $successState): void;
        abstract protected function createAcceptNotification($entity, EntityManagerInterface $manager): void;


    //////////////////////////////// Template Method pour le refus /////////////////////////////////////
    final protected function handleReject(
        $id,
        EntityManagerInterface $manager,
        string $entityClass,
        string $rejectedState,
        string $redirectRoute
    ): Response {
        $entity = $this->getDoctrine()->getRepository($entityClass)->find($id);
        
        if ($entity) {
            $this->updateEntityOnReject($entity, $rejectedState, $manager);
            $this->createRejectionNotification($entity, $manager);
            $manager->flush();
        }
        
        return $this->redirectToRoute($redirectRoute);
    }
    
    // Méthodes à implémenter
    abstract protected function updateEntityOnReject($entity, string $rejectedState, EntityManagerInterface $manager): void;
    abstract protected function createRejectionNotification($entity, EntityManagerInterface $manager): void;

    //////////////////// Template Method pour l'ajout /////////////////////////// 

    final protected function handleAdd(
        Request $request,
        string $redirectRoute,
        array $context = []
    ): Response {
        // 1. Création de l'entité
        $entity = $this->createNewEntity();
        
        // 2. Initialisation de l'entité
        $this->initNewEntity($entity);
        
        // 3. Création du formulaire
        $form = $this->createForm($this->getFormType(), $entity);
        $form->handleRequest($request);

        // 4. Traitement de la soumission
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            // Hooks avant persistance
            $this->prePersist($entity, $em);
            $em->persist($entity);
            $this->postPersist($entity, $em);
            
            $em->flush();
            
            // Post-flush actions
            $this->postFlush($entity, $request);
            
            return $this->redirectToRoute($redirectRoute);
        }

        // 5. Rendu du template
        $defaultContext = [
            'for' => $form->createView(),
            'entity' => $entity
        ];
        
        return $this->render(
            $this->getFormTemplate(),
            array_merge($defaultContext, $context)
        );
    }



    //////////////////// Template Method pour la modification //////////////////////////
    final protected function handleEdit(Request $request, $id, string $redirectRoute): Response 
    {
        // 1. Récupération de l'entité
        $entity = $this->getEntity($id, $this->getDoctrine()->getManager());
    
        // 2. Création du formulaire
        $form = $this->createForm($this->getFormType(), $entity);
        $form->handleRequest($request);
    
        // 3. Traitement de la soumission
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            // Hooks avant persistance
            $this->preEditPersist($entity, $em);
            $em->persist($entity);         
            $em->flush();
    
            // Pour addFlash
            $this->postEditSuccess($entity, $request);
    
            // Redirection
            return $this->redirectToRoute($redirectRoute);
        }
    
        // 4. Rendu du template si formulaire non soumis ou invalide
        return $this->render($this->getFormTemplate(), [
            'for' => $form->createView()
        ]);
    }

      


    // // Template Method pour la suppression
    // final protected function handleDelete(
    //     $id,
    //     EntityManagerInterface $em,
    //     string $redirectRoute
    // ): Response {
    //     $entity = $this->getEntity($id, $em);
    //     $this->preDelete($entity, $em);
    //     $em->remove($entity);
    //     $this->postDelete($entity, $em);
    //     $em->flush();

    //     return $this->redirectToRoute($redirectRoute);
    // }

     // Méthodes abstraites obligatoire
     abstract protected function createNewEntity();
     abstract protected function getFormType(): string;
     abstract protected function getFormTemplate(): string;
     abstract protected function getEntityClass(): string;
     
     // Hooks avec implémentation par défaut 
     protected function postEditSuccess($entity, Request $request): void {}
     protected function initNewEntity($entity): void {
         $entity->setDatedemande(new \DateTime());
         $entity->setEtat('en cours');
         $entity->setRh($this->getUser());
     }
     
     protected function prePersist($entity, EntityManagerInterface $em): void {}
     protected function postPersist($entity, EntityManagerInterface $em): void {}
     protected function postFlush($entity, Request $request): void {}
 
 

    // Méthodes avec implémentation par défaut
    protected function getEntity($id, EntityManagerInterface $em)
    {
        return $em->getRepository($this->getEntityClass())->find($id);
    }

    protected function processForm(
        Request $request,
        FormInterface $form,
        $entity
    ): bool {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $this->postFormProcess($entity, $em);
            $em->flush();
            return true;
        }
        return false;
    }

    // Hooks optionnels
    protected function prePersistAccept($entity, EntityManagerInterface $em): void {}
    protected function postPersistAccept($entity, EntityManagerInterface $em): void {}
    protected function prePersistRefuse($entity, EntityManagerInterface $em): void {}
    protected function postPersistRefuse($entity, EntityManagerInterface $em): void {}
    protected function preDelete($entity, EntityManagerInterface $em): void {}
    protected function postDelete($entity, EntityManagerInterface $em): void {}
    protected function postFormProcess($entity, EntityManagerInterface $em): void {}

    // Helper pour les notifications
    protected function createNotification(
        $relatedEntity,
        EntityManagerInterface $em,
        string $text,
        $receiver,
        $sender
    ): void {
        $notification = new Notification();
        $notification->setText($text);
        $notification->setDateNotification(new \DateTime());
        $notification->setRecepteur($receiver);
        $notification->setDestinateur($sender);
        $notification->setIsRead(0);

        // Relation spécifique selon l'entité
        if ($relatedEntity instanceof Conge) {
            $notification->setConge($relatedEntity);
        } elseif ($relatedEntity instanceof AvanceSalaire) {
            $notification->setAvanceSalaire($relatedEntity);
        }

        $em->persist($notification);
    }
}
