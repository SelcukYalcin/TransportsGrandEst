<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // traitez les données soumises ici
            // envoyez le courriel, sauvegardez les données en base de données, etc.
            return $this->redirectToRoute('app_home');
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),

        ]);
    }

    #[Route('/form', name: 'app_contact_form')]
    public function contactForm(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // traitez les données soumises ici
            // envoyez le courriel, sauvegardez les données en base de données, etc.
            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
