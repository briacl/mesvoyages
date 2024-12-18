<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;
use App\Form\VisiteType;
use App\Entity\Visite;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AdminVoyagesController
 *
 * @author blemeill
 */
class AdminVoyagesController extends AbstractController {
    
    private VisiteRepository $repository;
    private EntityManagerInterface $entityManager;
    
    public function __construct(VisiteRepository $repository, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }
    
    #[Route('/admin', name: 'admin.voyages')]
    public function index() : Response {
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    #[Route('/admin/suppr/{id}', name: 'admin.voyage.suppr')]
    public function suppr(int $id) : Response {
        $visite = $this->repository->find($id);
        $this->repository->remove($visite);
        return $this->redirectToRoute('admin.voyages');
    }
    
    #[Route('/admin/edit/{id}', name: 'admin.voyage.edit')]
    public function edit(int $id, Request $request) : Response {
        $visite = $this->repository->find($id);
        $formVisite = $this->createForm(VisiteType::class, $visite);
        
        $formVisite->handleRequest($request);
        if ($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->repository->add($visite);
            return $this->redirectToRoute('admin.voyages');
        }
        
        return $this->render("admin/admin.voyage.edit.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
    
    #[Route('/admin/ajout', name: 'admin.voyage.ajout', methods: ['GET', 'POST'])]
    public function ajout(Request $request) : Response {
        $visite = new Visite();
        
        //$visite->setDatecreation(new \DateTime('2024-12-31'));
        
        $formVisite = $this->createForm(VisiteType::class, $visite);
        
        $formVisite->handleRequest($request);
        
        //if ($formVisite->isSubmitted()) {
                //dd($formVisite->getData());
                //dd($request->request->all());
            //}
        
        if ($formVisite->isSubmitted() && $formVisite->isValid()) {
            
            //$entityManager = $this->getDoctrine()->getManager;
            $this->entityManager->persist($visite);
            $this->entityManager->flush();
             
            //$this->repository->add($visite);
            return $this->redirectToRoute('admin.voyages');
        }
        
        return $this->render("admin/admin.voyage.ajout.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
}
