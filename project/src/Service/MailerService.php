<?php

namespace App\Service;

use App\Entity\ResetPasswordToken;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportException
     */
    public function sendResetPasswordEmail(User $user, ResetPasswordToken $resetPasswordToken): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('choixoption@upjv.com', 'Choix Option UPJV'))
            ->to($user->getEmail())
            ->subject('Reinitialization de votre mot de passe')
            ->htmlTemplate('email/password_reset_request.twig')
            ->context([
                'user' => $user,
                'resetPasswordToken' => $resetPasswordToken,
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new TransportException($e->getMessage());
        }
    }
}