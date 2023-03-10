<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Event\BannedUserEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BannedUserChecker implements UserCheckerInterface
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function checkPreAuth(UserInterface $user)
    {
        // be sure that the User is an instance of User
        if (!$user instanceof User) {
            return;
        }

        // for some reason, the account is disabled/banned
        if ($user->isDisabled() === true) {
            $bannedUserEvent = new BannedUserEvent($user);
            $this->eventDispatcher->dispatch($bannedUserEvent);
//            $this->eventDispatcher->dispatch($bannedUserEvent, BannedUserEvent::NAME);

            throw new CustomUserMessageAccountStatusException(
                'Your account is banned. Please contact the admin'
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // yet the user's credentials are OK and the account is not banned,
        // we still need to verify that the account has been verified
        if ($user->isVerified() === false) {
            throw new CustomUserMessageAccountStatusException(
                'You need to verify your account'
            );
        }
    }
}
