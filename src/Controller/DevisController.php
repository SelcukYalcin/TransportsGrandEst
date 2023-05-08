<?php

namespace App\Controller;

use DateTime;
use App\Entity\Devis;
use App\Form\DevisType;
use App\Entity\Expediteur;
use App\Entity\Marchandise;
use App\Entity\Destinataire;
use App\Form\RechercheType;
use Symfony\Component\Mime\Email;
use App\Repository\DevisRepository;
use App\Service\Recherche;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/devis')]
class DevisController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,

    )
    {
    }

    #[Route('/list', name: 'app_devis_index', methods: ['GET'])]
    public function index(Request $request, DevisRepository $devisRepository, PaginatorInterface $paginatorInterface): Response
    {   
        $recherche = new Recherche();
        $form = $this->createForm(RechercheType::class, $recherche);
        $form->handleRequest($request);

        //$date = new DateTime('2022-01-01');
        // $devis = $devisRepository->findByDate($date);
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isGranted('ROLE_ADMIN')) {
            //Afficher tous les devis
            $data = $devisRepository->findAll();

        } else {
            // afficher que les devis du user connecté
            $data = $this->getUser()->getDevis();
        }

        if($form->isSubmitted() && $form->isValid()){
            $recherche = $this->em->getRepository(Devis::class)->RechercheDevis($recherche);
            $recherche = $paginatorInterface->paginate( $recherche, $request->query->getInt('page', 1), 5
            );

            return $this->render('devis/index.html.twig', [
                'devis' => $recherche,
                'form' => $form->createView(),
            ]);
        }

        $devis = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1), 7
        );

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DevisRepository $devisRepository, MailerInterface $mailer): Response
    {
        $devi = new Devis();
        $expediteur = new Expediteur();
        $expediteur->addDevis($devi);
        $destinataire = new Destinataire();
        $destinataire->addDevis($devi);
        $marchandise = new Marchandise();
        $marchandise->addDevis($devi);
        //Crée et renvoie une instance de formulaire à partir du type du formulaire(DevisType)
        $form = $this->createForm(DevisType::class, $devi);
        //Traiter la requête donnée        
        $form->handleRequest($request);
        // Si le formulaire est soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $devi->setDateVal(new \DateTime('now + 1 month'));
            if ($this->getUser()) {
                $devi->setMembre($this->getUser());
            }
            $email = $form['email']->getData();
            $data = $form->getData();
            // Envoie d'E-Mail de notification [Nouvelle demande de devis]
            $email = (new Email())
            ->from($email)
            ->to('contact@tgee.com')
            ->subject('Nouvelle demande de devis')
            ->text('Vous avez reçu une nouvelle demande de devis.'. $devi->getExpediteur()->getNom())
            ->html($this->renderView('devis/devismail.html.twig', ['data' => $data]));

            $mailer->send($email);
            $devisRepository->save($devi, true);
            $this->addFlash(type: "success", message: "Votre demande de devis a été enregistré, elle sera traitée dans les plus brefs délais");
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,

        ]);
    }

    #[Route('/show/{id}', name: 'app_devis_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // afficher que les devis de l'User connecté
            $userConnecter = $this->getUser();
            $userDevis = $devi->getMembre();
            if ($userConnecter !== $userDevis) {
                throw new AccessDeniedException();
            }
        }

        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_devis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DevisRepository $devisRepository, Devis $devi): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // afficher que les devis de l'User connecté
            $userConnecter = $this->getUser();
            $userDevis = $devi->getMembre();
            if ($userConnecter !== $userDevis) {
                throw new AccessDeniedException();
            }
        }

        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $devisRepository->save($devi, true);
            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{id}', name: 'app_devis_delete', methods: ['POST'])]
    public function delete(Request $request, DevisRepository $devisRepository, Devis $devi): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isCsrfTokenValid('delete' . $devi->getId(), $request->request->get('_token'))) {
            $devisRepository->remove($devi, true);
        }
        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
