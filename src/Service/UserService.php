<?php

namespace App\Service;

use App\Entity\User;
use DateTime;

class UserService
{

    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    // Action Créer un token et envoyer un Mail
    public function createTokenAndSendEmail(User $user): void
    {
        // Générer un token
        $user->setToken($this->generateToken());
        // Assigne la date courante  date du token
        $user->setDateToken(new DateTime());

        $this->mailer->sendEmail($user->getEmail(), $user->getToken());
    }

    // Action qui génére un token aléatoire
    private function generateToken($length = 16): string
    {
        $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringLength = strlen($stringSpace);
        $string = str_repeat($stringSpace, ceil($length / $stringLength));
        $shuffledString = str_shuffle($string);
        $randomString = substr($shuffledString, 1, $length);
        return $randomString;
    }

}