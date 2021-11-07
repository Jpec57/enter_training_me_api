<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
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

        /** @var User $user */
        $user = $this->security->getUser();
        $training->setAuthor($user);

        $now = new \DateTime();
        $exos = $training->getExercises();

        if ($user && $exos) {
            if ($exos) {
                foreach ($exos as $exo) {
                    $exoRef = $exo->getExerciseReference();
                    $exoStrainess = $exoRef->getStrainessFactor();
                    $muscleActivations = $exoRef->getMuscleActivations();

                    $sets = $exo->getSets();
                    if ($sets) {
                        foreach ($sets as $set) {
                            $set->setUser($user);
                            $set->setRealisedDate($now);
                        }
                    }
                    $fitnessProfile = $user->getFitnessProfile();
                    if ($fitnessProfile) {
                        $intensity = $exo->getIntensity();
                        if ($muscleActivations) {
                            foreach ($muscleActivations as $muscleActivation) {
                                $muscle = $muscleActivation->getMuscle();
                                $amount = $intensity * $muscleActivation->getActivationRatio() * (1 + $exoStrainess);
                                switch ($muscle) {
                                    case "biceps":
                                        $fitnessProfile->setBicepsExperience($amount + $fitnessProfile->getBicepsExperience());
                                        break;
                                    case "triceps":
                                        $fitnessProfile->setTricepsExperience($amount + $fitnessProfile->getTricepsExperience());
                                        break;
                                    case "shoulders":
                                        $fitnessProfile->setShoulderExperience($amount + $fitnessProfile->getShoulderExperience());
                                        break;
                                    case "back":
                                        $fitnessProfile->setBackExperience($amount + $fitnessProfile->getBackExperience());
                                        break;
                                    case "chest":
                                        $fitnessProfile->setChestExperience($amount + $fitnessProfile->getChestExperience());
                                        break;
                                    case "calf":
                                        $fitnessProfile->setCalfExperience($amount + $fitnessProfile->getCalfExperience());
                                        break;
                                    case "quadriceps":
                                        $fitnessProfile->setQuadricepsExperience($amount + $fitnessProfile->getQuadricepsExperience());
                                        break;
                                    case "abs":
                                        $fitnessProfile->setAbsExperience($amount + $fitnessProfile->getAbsExperience());
                                        break;
                                    case "hamstring":
                                        $fitnessProfile->setHamstringExperience($amount + $fitnessProfile->getHamstringExperience());
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }



        // $message = (new \Swift_Message('A new book has been added'))
        //     ->setFrom('system@example.com')
        //     ->setTo('contact@les-tilleuls.coop')
        //     ->setBody(sprintf('The book #%d has been added.', $book->getId()));

        // $this->mailer->send($message);
    }
}
