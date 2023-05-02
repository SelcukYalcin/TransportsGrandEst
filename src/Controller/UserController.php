<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\Mailer;
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user')]
class UserController extends AbstractController
{

    private UserService $userService;
    private $mailer;
    private $em;

    public function __construct(
        private UserRepository $userRepository,
        Mailer $mailer,
        EntityManagerInterface $em,
        UserService $userService
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->userService = $userService;
    }


    #[Route('/list', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    // Action qui crée un nouvel User
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // User est un nouvel User
        $user = new User();
        // Crée et renvoie une instance de formulaire à partir du type de formulaire (içi UserType).
        $form = $this->createForm(UserType::class, $user);
        // Demande du formulaire
        $form->handleRequest($request);
        // Si le formulaire est soumis et est valide
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
        $this->denyAccessUnlessGranted('ROLE_USER');
        $userConnecter = $this->getUser();
        $user = $this->userRepository->find($id);
        if (!$this->isGranted('ROLE_ADMIN')) {
            if ($userConnecter !== $user) {
                throw new AccessDeniedException();
            }
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, User $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->userRepository->find($id);
        $isAdmin = in_array("ROLE_ADMIN", $user->getRoles());

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($isAdmin) {
                // Est-ce que je l'ai encore ?
                //          in_array()
                // $user a un role ROLE_ADMIN
                $isEncoreAdmin = in_array("ROLE_ADMIN", $user->getRoles());
                if (!$isEncoreAdmin) {
                    $nbAdmin = $this->userRepository->countAdmin();
                    if ($nbAdmin <= 1) {
                        //INterdire la modification
                        $this->addFlash('danger', 'Il doit rester au moins un administrateur.');
                        return $this->redirectToRoute('app_user_index');
                    }
                }
                //Si oui, alors est-ce que il y'en a un autre en BDD ?
                // requet au repo UserRepository en cherchant si ROLE_ADMIN existe
                //          SI oui, alors on autorise le changement -> on continue
                //          Si non, alors on refuse la modification -> redirect avec message flash danger
            }

            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $userRepository->save($user, true);
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository, DevisRepository $devisRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $devisToUpdate = $devisRepository->findBy([
                'membre' => $user
            ]);

            foreach ($devisToUpdate as $deviToUpdate) {
                $deviToUpdate->setMembre(null);
                $devisRepository->save($deviToUpdate, true);
            }

            if ($user->getId() !== $this->getUser()->getId()) {
                $userRepository->remove($user, true);
            } else {
                return $this->redirectToRoute('app_user_index');
            }
        }
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/suppresionProfil', name: 'profil_suppression')]
    public function suppressionProfil(Request $request, User $user, TokenStorageInterface $tokenStorage, DevisRepository $devisRepository): Response
    {
        if ($request->attributes->get('user')->getToken()) {
            $devisToUpdate = $devisRepository->findBy([
                'membre' => $user
            ]);
            foreach ($devisToUpdate as $deviToUpdate) {
                $deviToUpdate->setMembre(null);
                $devisRepository->save($deviToUpdate, true);
            }
            $request->getSession()->invalidate();
            $tokenStorage->setToken(null);
            $this->em->remove($user);
            $this->em->flush();
        }
        $this->addFlash('success', 'Votre compte a bien été supprimée');
        return $this->redirectToRoute('app_logout');
    }
}
