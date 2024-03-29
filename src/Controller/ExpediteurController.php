<?php

namespace App\Controller;

use App\Entity\Expediteur;
use App\Form\ExpediteurType;
use App\Repository\ExpediteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/expediteur')]
class ExpediteurController extends AbstractController
{
    #[Route('/', name: 'app_expediteur_index', methods: ['GET'])]
    public function index(ExpediteurRepository $expediteurRepository): Response
    {
        return $this->render('expediteur/index.html.twig', [
            'expediteurs' => $expediteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_expediteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $expediteur = new Expediteur();
        $form = $this->createForm(ExpediteurType::class, $expediteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($expediteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_expediteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expediteur/new.html.twig', [
            'expediteur' => $expediteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expediteur_show', methods: ['GET'])]
    public function show(Expediteur $expediteur): Response
    {
        return $this->render('expediteur/show.html.twig', [
            'expediteur' => $expediteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_expediteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expediteur $expediteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExpediteurType::class, $expediteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_expediteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expediteur/edit.html.twig', [
            'expediteur' => $expediteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_expediteur_delete', methods: ['POST'])]
    public function delete(Request $request, Expediteur $expediteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expediteur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($expediteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_expediteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
