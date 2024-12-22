<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\EnvironnementRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Environnement;

/**
 * Description of AdminEnvironnementController
 *
 * @author blemeill
 */
class AdminEnvironnementController extends AbstractController {
    
    private $repository;
    
    public function __construct(EnvironnementRepository $repository) {
        $this->repository = $repository;
    }
    
    #[Route('/admin/environnements', name: 'admin.environnements', methods: ['GET', 'POST'])]
    public function index() : Response {
        
        //dd('Controller called');
        
        //return new Response('Page Voyages accessible');
        
        $environnements = $this->repository->findAll();
        return $this->render("admin/admin.environnements.html.twig", [
            'environnements' => $environnements
        ]);
    }
    
    #[Route('/admin/environnement/suppr/{id}', name: 'admin.environnement.suppr')]
    public function suppr(int $id) : Response {
        $environnement = $this->repository->find($id);
        $this->repository->remove($environnement);
        return $this->redirectToRoute('admin.environnements');
    }
    
    #[Route('/admin/enivronnement/ajout', name: 'admin.environnement.ajout')]
    public function ajout(Request $request) : Response {
        $nomEnvironnement = $request->get("nom");
        $environnement = new Environnement();
        $environnement->setNom($nomEnvironnement);
        $this->repository->add($environnement);
        return $this->redirectToRoute('admin.environnements');
    }
    
}
