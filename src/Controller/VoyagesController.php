<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VisiteRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of VoyagesController
 *
 * @author blemeill
 */
class VoyagesController extends AbstractController{
    
    private $repository;
    
    public function __construct(VisiteRepository $repository) {
        $this->repository = $repository;
    }
    
    #[Route('/voyages', name: 'voyages', methods: ['GET'])]
    public function index(Request $request): Response {
        
        //dd('Controller called');
        
        //return new Response('Page Voyages accessible');
        
            $champ = $request->query->get('champ', 'ville');
            $ordre = $request->query->get("ordre", 'ASC');
            //$valeur = $request->query->get('recherche', null);

            if (!in_array($ordre, ['ASC', 'DESC'])) {
                $ordre = 'ASC';
            }
            
            $allowFields = ['ville', 'pays', 'note', 'datecreation'];
            if (!in_array($champ, $allowFields)) {
                
                throw new \InvalidArgumentException("Invali sorting field: $champ");
            }
            
            if ($champ) {
                    $visites = $this->repository->findAllOrderBy($champ, $ordre);
            } else {
                    $visites = $this->repository->findAll();
            }

            return $this->render('pages/voyages.html.twig', [
                    'visites' => $visites,
            ]);
        
    }

    #[Route('/voyages/recherche/{champ}', name: 'voyages.findallequal', methods: ['GET', 'POST'])]
    public function findAllEqual($champ, Request $request) : Response {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))) {

            // Récupérer la valeur de la recherche
            $valeur = $request->query->get('recherche');

            // Si un filtre est appliqué (valeur non vide)
            if ($valeur) {
                // Filtrer les visites en fonction de la valeur
                $visites = $this->repository->findByEqualValue($champ, $valeur);
            } else {
                // Si aucun filtre n'est appliqué, récupérer toutes les visites
                $visites = $this->repository->findAll();
            }

            // Rendu de la vue avec les visites filtrées ou toutes les visites
            return $this->render('pages/voyages.html.twig', [
                'visites' => $visites,
            ]);
        }

        // Redirection si le token CSRF est invalide
        return $this->redirectToRoute('voyages');
    }
    
    #[Route('/voyages/voyage/{id}', name: 'voyages.showone', methods: ['GET'])]
    public function showOne(int $id) : Response {
        $visite = $this->repository->find($id);
        return $this->render("pages/voyage.html.twig", [
            'visite' => $visite
        ]);
    }
}
