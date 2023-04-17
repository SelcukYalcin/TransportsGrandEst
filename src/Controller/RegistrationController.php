<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Service\UserService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    const EXPIRATION_TIME = 1;

    private Mailer $mailer;
    private EntityManagerInterface $em;
    private UserService $userService;

    public function __construct(Mailer $mailer, EntityManagerInterface $em, UserService $userService)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->userService = $userService;
    }

    //-------------- Action d'inscription : appele fonction register de mon controller
    #[Route('/register', name: 'app_register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository              $userRepository
    ): Response
    {
//-------------- On crée un new User et on le met en bdd new user est à l'etat non verifié
        $user = new User();
//-------------- Afficher formulaire
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
//-------------- Si formulaire submitted && is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Si il n'y a aucun utilisateur, alors on crée l'utilisateur admin
            if (count($userRepository->findAll()) === 0) {
                $user->setRoles([
                    'ROLE_USER',
                    'ROLE_ADMIN'
                ]);
                $user->setIsVerified(true);
            } else {
//-------------- Créer le token et le stocker en bdd
//-------------- Envoi d'un email: un lien de confirmation qui contient un token (suite de charactere generé en aléatoire)
                $this->userService->createTokenAndSendEmail($user);
            }

            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash(type: "success", message: "Inscription réussie ! ");

            return $this->redirectToRoute('app_home');

        } elseif ($form->isSubmitted() && $user->getEmail()) {
            /** @var User $user */
            $user = $userRepository->findOneBy([
                'email' => $user->getEmail(),
            ]);

            $this->userService->createTokenAndSendEmail($user);
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash(type: "danger", message: "Renvoi mail vérification ! ");

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    // Action confirmation email
    public function verifyUserEmail(
        Request             $request,
        TranslatorInterface $translator,
        UserRepository      $userRepository
    ): Response
    {
        $token = $request->query->get('token');
        // - on récupere l'user (findOneBy UserRepository)
        if (!$token) {
            throw new AccessDeniedException();
        }

        $user = $userRepository->findOneBy([
            'token' => $token,
        ]);
        if (!$user) {
            throw new AccessDeniedException();
        }

        $userDateToken = $user->getDateToken();
        $now = new DateTime();
        $diff = $now->diff($userDateToken);
        $hoursDiff = (int)$diff->format('%h');

        //-------- On vérifie si le token a expiré ou non → SI la différence est inférieure à EXPIRATION_TIME si n'a pas expiré:
        if (self::EXPIRATION_TIME > $hoursDiff) {
            // Alors vérification acceptée
            $this->addFlash('success', 'Email confirmé !');
            //On passe le champ isVerified à true
            $user->setIsVerified(true);
            $this->em->flush();
        } else {

            // Sinon refusé -> message flash erreur
            $this->addFlash('danger', 'Session expiré !');
            // on redirige vers page d'inscription'
            return $this->redirectToRoute('app_register');
        }
        // On redirige vers la connexion avec un message flash de succès
        return $this->redirectToRoute('app_login');
    }


}
