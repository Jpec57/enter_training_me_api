<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailerInterface)
    {
        $this->mailer = $mailerInterface;
    }

    public function sendEmail()
    {
        $senderEmail = 'enter.training.me@gmail.com';
        $receiverMail = 'jean.paul.bella@hotmail.fr';
        $email = (new Email())
            ->from($senderEmail)
            ->to($receiverMail)
            ->replyTo($senderEmail)
            // ->priority(Email::PRIORITY_HIGH)
            ->subject('Your training')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);
    }
}
