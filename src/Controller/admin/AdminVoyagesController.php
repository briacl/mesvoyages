<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use App\Repository\EnvironnementRepository;
//use App\Repository\Environnement;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\VisiteType;
use App\Entity\Visite;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Description of AdminVoyagesController
 *
 * @author blemeill
 */
class AdminVoyagesController extends AbstractController {
    
    private VisiteRepository $repository;
    private EntityManagerInterface $entityManager;
    private $params;
    
    public function __construct(VisiteRepository $repository, EntityManagerInterface $entityManager, ParameterBagInterface $params) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->params = $params;
    }
    
    #[Route('/admin', name: 'admin.voyages', methods: ['GET', 'POST'])]
    public function index(Request $request) : Response {
        
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
        
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    #[Route('/admin/voyages/suppr/{id}', name: 'admin.voyage.suppr')]
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
            /* @var UploadedFile $file */
            $file = $formVisite->get('imageFile')->getData();
            
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();
                
                /*try {
                    $file->move(
                            $this->params->get('kernel.project_dir').'/pubolic/images/visites',
                            $newFilename
                    );
                } catch (FileExpetion $e) {}
                $visite->setImageName($newFilename);*/
            }
            $visite->setImageName($newFilename);
            
            $this->repository->add($visite);
            
            $this->entityManager->persist($visite);
            $this->entityManager->flush();
            
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
