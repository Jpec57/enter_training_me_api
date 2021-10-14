<?php

namespace App\Services;

use DateTime;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailerInterface)
    {
        $this->mailer = $mailerInterface;
    }

    public function sendTrainingEmail(string $trainingContent)
    {
        $senderEmail = 'enter.training.me@gmail.com';
        $receiverMail = 'jean.paul.bella@hotmail.fr';
        $date = new DateTime();
        $email = (new Email())
            ->from($senderEmail)
            ->to($receiverMail)
            ->replyTo($senderEmail)
            // ->priority(Email::PRIORITY_HIGH)
            ->subject('Your training from ' + $date->toStrin)
            ->html("<code>$trainingContent</code>");

        $this->mailer->send($email);
    }
}
