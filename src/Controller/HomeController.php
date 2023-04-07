<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Form\DevisTrajetType;
use App\Repository\DevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
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
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),

        ]);
    }


}