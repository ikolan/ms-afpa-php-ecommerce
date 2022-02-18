<?php

namespace App\Controller;

use App\Data\ContactData;
use App\Data\Mailing;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contactForm', methods: ["GET"])]
    public function contactForm(): Response
    {
        $form = $this->createForm(ContactType::class);
        return $this->render('contact/contact.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route("/contact", name: "contact", methods: ["POST"])]
    public function contact(Request $request): Response
    {
        $contactData = new ContactData();
        $form = $this->createForm(ContactType::class, $contactData);
        $form->handleRequest($request);

        $mailing = new Mailing();
        $mailing->send(3647349, "jeanmarkdurand@gmail.com", "Nom du site", $contactData->subject, [
            "email" => $contactData->email,
            "subject" => $contactData->subject,
            "message" => $contactData->content
        ]);

        return new RedirectResponse($this->generateUrl("contactForm", ["sended" => true]));
    }
}
