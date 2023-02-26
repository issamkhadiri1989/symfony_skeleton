<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;

use Symfony\Contracts\EventDispatcher\Event;

class BannedUserEvent extends Event
{
    public const NAME = 'event.name';

    public function __construct(private readonly User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
