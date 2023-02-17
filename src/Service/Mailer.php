<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer {
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail($email, $token): void
    {
        $this->sendCustomEmail($email,'emails/registration.html.twig', ['token' => $token],'Transports - Validation de l\'inscription');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendCustomEmail($email, $template, $context,$subject,$from = 'no-reply@transports.com'): void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to(new Address($email))
            ->subject($subject)
            // path of the Twig template to render
            ->htmlTemplate($template)
            // pass variables (name => value) to the template
            ->context($context)
            ;
        $this->mailer->send($email);
    }
}