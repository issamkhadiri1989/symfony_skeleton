<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\User;
use App\Service\Security\User as UserManager;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

//#[AsEventListener]
class LoginListener
{
    public function __construct(private readonly UserManager $manager)
    {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $this->manager->updateLoginDate($user);
    }

    /*public function preListen(LoginSuccessEvent $event): void
    {

    }

    public function postListen(LoginSuccessEvent $event): void
    {

    }*/
}
