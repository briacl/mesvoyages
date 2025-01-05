<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//use App\Repository\EnvironnementRepository;
//use App\Repository\Environnement;
use Symfony\Component\HttpFoundation\Request;
//use App\Repository\VisiteRepository;
//use Doctrine\ORM\EntityManagerInterface;
use App\Form\ContactType;
use App\Entity\Contact;
//use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Description of ContactController
 *
 * @author blemeill
 */
class ContactController extends AbstractController {
    
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response {
        
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            //envoi du mail
            
            $this->sendEmail($mailer, $contact);
            $this->addFlash('succès', 'message envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render("pages/contact.html.twig", [
            'contact => $contact',
            'formcontact' => $formContact->createView()
        ]);
    }
    
    public function sendEmail(MailerInterface $mailer, Contact $contact) {
        $email = (new Email())
                ->from($contact->getEmail())
                ->to('contact@mesvoyages.com')
                ->subject('Message du site de voyage')
                ->html($this->renderView(
                        'pages/_email.html.twig', [
                            'contact' => $contact
                        ]
                ),
            )
        ;
        $mailer->send($email);
    }
}
