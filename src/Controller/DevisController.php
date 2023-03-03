<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/devis')]
class DevisController extends AbstractController
{
    public function __construct(
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
