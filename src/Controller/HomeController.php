<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Form\DevisTrajetType;
use Symfony\Component\Mime\Email;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, Contact $contact = null, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // traitez les données soumises ici
            $contact = $form->getData();
            $em->persist($contact);
            $em->flush();
            // Envoie de Mail de notification [Nouvelle demande de contact]
            $email = $form['email']->getData();
            $email = (new Email())
            ->from($email)
            ->to('contact@tgee.com')
            ->subject('Nouvelle demande de contact')
            ->text('Vous avez reçu une nouvelle demande de contact.');
            // envoyez le courriel, sauvegardez les données en base de données, etc.
            $mailer->send($email);
            // Message flash 
            $this->addFlash(type: "success", message: "Votre message a été enregistré");

            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),

        ]);
    }


}