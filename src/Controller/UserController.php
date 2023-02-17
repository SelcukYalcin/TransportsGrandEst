<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(
        private UserRepository $userRepository, Mailer $mailer, EntityManagerInterface $em, UserService $userService)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->userService = $userService;
    }


    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    //Action qui crée un nouvel User
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        //user est un nouvel user
        $user = new User();
        //Affiche le formulaire
        $form = $this->createForm(UserType::class, $user);
        //Demande du formulaire

        $form->handleRequest($request);


        //Si le formulaire est soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->userService->createTokenAndSendEmail($user);

            $this->userRepository->save($user, true);

            $this->addFlash('success', "L'utilisateur " . $user->getEmail() . " est inscrit");

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        } elseif ($form->isSubmitted() && $user->getEmail()) {
            /** @var User $user */
            $user = $this->userRepository->findOneBy([
                'email' => $user->getEmail(),
            ]);


            $this->userService->createTokenAndSendEmail($user);

            $this->userRepository->save($user, true);

            $this->addFlash(type: "danger", message: "Renvoi mail vérification ! ");

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_user_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show($id): Response
    {
        $user = $this->userRepository->find($id);
//        dd($user);
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id, UserRepository $userRepository): Response
    {
        $user = $this->userRepository->find($id);


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}