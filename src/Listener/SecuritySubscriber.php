<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\User as UserEntity;
use App\Event\BannedUserEvent;
use App\Service\Mailer;
use App\Service\Security\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class SecuritySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly User $manager,
        private readonly Mailer $mailer,
        private readonly string $adminAddress
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'updateLoginDate',
            BannedUserEvent::class => 'sendMailOnBannedTry',
//            BannedUserEvent::NAME => 'sendMailOnBannedTry',
            /*LoginSuccessEvent::class => [
                ['preListen', 2],
                ['postListen', 1]
            ],*/
        ];
    }

    /*public function preListen(LoginSuccessEvent $event): void
    {
    }

    public function postListen(LoginSuccessEvent $event): void
    {
    }*/

    /**
     * This event is triggered when log in is successful. It updates the connection time.
     *
     * @param LoginSuccessEvent $event
     *
     * @return void
     */
    public function updateLoginDate(LoginSuccessEvent $event): void
    {
        /** @var UserEntity $user */
        $user = $event->getUser();
        $this->manager->updateLoginDate($user);
    }

    /**
     * Send the notification mail to admin when a banned account is trying to log in.
     * This function is triggered when the event BannedUserEvent is fired. {@see BannedUserChecker}
     *
     * @param BannedUserEvent $event
     *
     * @return void
     */
    public function sendMailOnBannedTry(BannedUserEvent $event): void
    {
        $user = $event->getUser();
        $this->mailer->sendMailNotification(
            'email/content.html.twig',
            'Banned account login attempt',
            $this->adminAddress,
            $user->getFullName(),
            [
                'time' => new \DateTimeImmutable(),
                'username' => $user->getUserIdentifier(),
            ]
        );
    }
}
