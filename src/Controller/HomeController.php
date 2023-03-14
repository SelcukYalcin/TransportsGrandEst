<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisTypeTrajet;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

//    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
//    public function new(Request $request, DevisRepository $devisRepository): Response
//    {
//        $devi = new Devis();
//        $form = $this->createForm(DevisTypeTrajet::class, $devi);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $devi->setDateVal(new \DateTime('now + 1 month'));
//
//            if ($this->getUser()) {
//                $devi->setMembre($this->getUser());
//            }
//            $devisRepository->save($devi, true);
//
//            return $this->redirectToRoute('app_devis_index', [], Response::HTTP_SEE_OTHER);
//        }
//        return $this->renderForm('devis/new.html.twig', [
//            'devi' => $devi,
//            'form' => $form,
//        ]);
//    }

}