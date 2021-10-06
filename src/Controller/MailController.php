<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailController extends AbstractController
{

    public function __construct()
    {
    }

    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer): Response
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

        $mailer->send($email);


        return $this->json(['res' => 'ok'], 200);
    }
}
