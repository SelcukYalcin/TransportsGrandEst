<?php

namespace App\Controller;

use App\Entity\Destinataire;
use App\Entity\Devis;
use App\Entity\Expediteur;
use App\Entity\Marchandise;
use App\Entity\User;
use App\Form\DestinataireType;
use App\Form\DevisType;
use App\Form\ExpediteurType;
use App\Form\MarchandiseType;
use App\Form\UserType;
use App\Repository\DevisRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/devis')]
class DevisController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        DevisRepository $devisRepository,
        UserRepository  $userRepository
    )
    {
    }

    #[Route('/list', name: 'app_devis_index', methods: ['GET'])]
    public function index(DevisRepository $devisRepository, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isGranted('ROLE_ADMIN')) {
            //Afficher tous les devis
            $devis = $devisRepository->findAll();
        } else {
            // afficher que les devis du user connecté
            $devis = $this->getUser()->getDevis();
        }

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,

        ]);
    }

    #[Route('/new', name: 'app_devis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DevisRepository $devisRepository): Response
    {
        $devi = new Devis();

        $expediteur = new Expediteur();
        $expediteur->addDevis($devi);
        $destinataire = new Destinataire();
        $destinataire->addDevis($devi);

        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devi->setDateVal(new \DateTime('now + 1 month'));

            if ($this->getUser()) {
                $devi->setMembre($this->getUser());
            }
            $devisRepository->save($devi, true);

            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
        }

//        $expediteur = new Expediteur();
//        $formExpediteur = $this->createForm(ExpediteurType::class, $expediteur);
//        $formExpediteur->handleRequest($request);
//        if ($formExpediteur->isSubmitted() && $formExpediteur->isValid()) {
//            $this->em->persist($expediteur);
//            $this->em->flush();
//        }
//
//        $destinataire = new Destinataire();
//        $formDestinataire = $this->createForm(DestinataireType::class, $destinataire);
//        $formDestinataire->handleRequest($request);
//        if ($formDestinataire->isSubmitted() && $formDestinataire->isValid()) {
//            $this->em->persist($destinataire);
//            $this->em->flush();
//        }
//
//        $marchandise = new Marchandise();
//        $formMarchandise = $this->createForm(MarchandiseType::class, $marchandise);
//        $formMarchandise->handleRequest($request);
//        if ($formMarchandise->isSubmitted() && $formMarchandise->isValid()) {
//            $this->em->persist($marchandise);
//            $this->em->flush();
//        }
//
//        $user = new User();
//        $formUser = $this->createForm(UserType::class, $user);
//        $formUser->handleRequest($request);
//        if ($formUser->isSubmitted() && $formUser->isValid()) {
//            $this->em->persist($user);
//            $this->em->flush();
//        }

        return $this->renderForm('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
//            'formExpediteur' => $formExpediteur,
//            'formDestinataire' => $formDestinataire,
//            'formMarchandise' => $formMarchandise,
//            'formUser' => $formUser
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
    public function edit(Request $request, int $id, Devis $devi, DevisRepository $devisRepository): Response
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
    public function delete(Request $request, Devis $devi, DevisRepository $devisRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isCsrfTokenValid('delete' . $devi->getId(), $request->request->get('_token'))) {
            $devisRepository->remove($devi, true);
        }
        return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
    }
}
