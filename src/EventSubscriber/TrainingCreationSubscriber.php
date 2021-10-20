<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Book;
use App\Entity\Training;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class TrainingCreationSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::PRE_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $training = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$training instanceof Training || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->security->getUser();
        $training->setAuthor($user);

        // $message = (new \Swift_Message('A new book has been added'))
        //     ->setFrom('system@example.com')
        //     ->setTo('contact@les-tilleuls.coop')
        //     ->setBody(sprintf('The book #%d has been added.', $book->getId()));

        // $this->mailer->send($message);
    }
}
