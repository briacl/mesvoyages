<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;
//use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AdminVoyagesController
 *
 * @author blemeill
 */
class AdminVoyagesController extends AbstractController {
    
    private $repository;
    
    public function __construct(VisiteRepository $repository) {
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
}
