<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    const EXPIRATION_TIME = 5;


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/oublie-pass', name: 'forgotten_password')]
    public function forgottenPassword(
        Request                 $request,
        UserRepository          $userRepository,
        TokenGeneratorInterface $tokenGeneratorInterface,
        EntityManagerInterface  $em,
        Mailer                  $email
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class,);
        // Traiter le formulaire handleRequest : Gère la requête sauf qu'il faut lui passer une requête ($request)
        // Pour récupérer ($request) on utilise l'injection de dépendance ou on va injecter Request (composant de HttpFoundation)
        $form->handleRequest($request);
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On va chercher l'utilisateur par son email avec la methode findOneByEmail
            // On injecte UserRepository $userRepository (import class !!)
            // On va récuperer dans le formulaire l'email qui a été renseigné ($form->get('email')
            $user = $userRepository->findOneBy([
                'email' => $form->get('email')->getData(), // La fonction getData récupère les données qui sont dans le champ 'email' de mon formulaire
                'isVerified' => true
            ]);

            // On vérifie si on a un utilisateur
            if ($user) {
                // On génère un token unique de réinitialisation en utilisant un service de symfony (TokenGeneratorInterface) que l'on injecte
                $userToken = $tokenGeneratorInterface->generateToken();
                // On attribue un nouveau token($userToken)
                $user->setUserToken($userToken);
                $user->setUserDateToken(new DateTime());

                $em->persist($user);
                $em->flush();

                // On génère un lien de réinitialisation du mot de passe en utilisant une fonctionalité de l'AbstractController : generateUrl
                // Je lui passe en paramètres le nom de ma route 'reset_pass', token est mon $token et je vais utiliser UrlGneretaorInterface
                // et aller chercher une de ses propriétés qui s'appele ABSOLUTE_URL (url complète qui sera dans le mail)
                $url = $this->generateUrl('reset_pass', ['userToken' => $userToken],
                    UrlGeneratorInterface::ABSOLUTE_URL);
                // On crée les données du mail
                // "compact" est équivalent à faire un tableau ['url' => $url, 'user' => $user]
                $context = compact('url', 'user');
                // Envoi du mail
                $email->sendCustomEmail($user->getEmail(), 'emails/password_reset.html.twig', $context, 'Transports - Réinitialisation de votre mot de passe');
                // Affciche un message 'success'
                $this->addFlash(type: 'success', message: 'Email de réinitialisation de mot de passe envoyé avec succès');
                // On redirige vers la page de connexion
                return $this->redirectToRoute('app_login');

            }
            // $user est null
            $this->addFlash(type: 'danger', message: 'Un probleme est survenu');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            // Tu me crées la vue html de mon formulaire et tu la passes sous le nom requestPassForm
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/oublie-pass/{userToken}', name: 'reset_pass')]
    public function resetPass(
        string                      $userToken,
        Request                     $request,
        UserRepository              $userRepository,
        EntityManagerInterface      $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base de données
        $user = $userRepository->findOneByUserToken($userToken);
        if (!$user) {
            dd('no user found');
        }

        $userDateToken = $user->getUserDateToken();
        $now = new DateTime();
        $diff = $now->diff($userDateToken);
        $hoursDiff = (int)$diff->format('%i');

        //-------- On vérifie si le token a expiré ou non → SI la différence est inférieure à EXPIRATION_TIME il n'a pas expiré
        if (!self::EXPIRATION_TIME > $hoursDiff) {
            // Sinon refusé -> message flash erreur
            $this->addFlash('danger', 'Délai de réinitialisation de mot de passe expiré !');
            // on redirige vers page d'inscription'
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On efface le Token
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em->flush();
            $this->addFlash('success', 'Changement de mot de passe validé !');
            return $this->redirectToRoute('app_login');
        }
        // On redirige vers la connexion avec un message flash de succès

        return $this->render('security/reset_password.html.twig', [
            'passForm' => $form->createView()
        ]);

    }


}
