<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractTypeController extends AbstractController
{
    /** @var string */
    protected $entityClass;
    /** @var string */
    protected  $formTypeClass;
    /** @var string */
    protected  $indexRoute;
    /** @var string */
    protected  $indexTemplate;
    /** @var string */
    protected  $addEditTemplate;

    public function index(EntityManagerInterface $em): Response
    {
        $items = $em->getRepository($this->entityClass)->findAll();
        return $this->render($this->indexTemplate, [
            'items' => $items
        ]);
    }

    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $entity = new $this->entityClass();
        $form = $this->createForm($this->formTypeClass, $entity);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute($this->indexRoute);
        }

        return $this->render($this->addEditTemplate, [
            'form' => $form->createView()
        ]);
    }

    public function delete($id, EntityManagerInterface $em): Response
    {
        $entity = $em->getRepository($this->entityClass)->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Entity not found');
        }

        if (method_exists($this, 'preDeleteCheck')) {
            $preDeleteResponse = $this->preDeleteCheck($entity);
            if ($preDeleteResponse !== null) {
                return $preDeleteResponse;
            }
        }

        try {
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Item deleted successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error during deletion: ' . $e->getMessage());
        }

        return $this->redirectToRoute($this->indexRoute);
    }
}

