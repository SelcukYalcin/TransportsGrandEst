<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/livraisons')]
class LivraisonController extends AbstractController
{
    public function __construct(
        private LivraisonRepository $livraisonRepository
    )
    {
    }

    #[Route('/list', name: 'app_livraison_index', methods: ['GET'])]
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isGranted('ROLE_ADMIN'))
        {
            // Afficher toutes les livraisons
            $livraison = $livraisonRepository->findAll();
        } else {
            // Afficher que les livraisons de l'User connecté
            $livraison = $this->getUser()->getLivraisons();
        }
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraison,
        ]);
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->livraisonRepository->save($livraison, true);
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_livraison_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show( Livraison $livraisons): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (!$this->isGranted('ROLE_ADMIN')) {
            // afficher que les devis de l'User connecté
            $userConnecter = $this->getUser();
            $userLivraison = $livraisons->getMembre();
            if ($userConnecter !== $userLivraison) {
                throw new AccessDeniedException();
            }
        }
//        $livraison = $this->livraisonRepository->find($id);
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraisons,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, LivraisonRepository $livraisonRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $livraison = $this->livraisonRepository->find($id);
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $livraisonRepository->save($livraison, true);
            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, LivraisonRepository $livraisonRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete' . $livraison->getId(), $request->request->get('_token')))
        {
            $livraisonRepository->remove($livraison, true);
        }
        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}
