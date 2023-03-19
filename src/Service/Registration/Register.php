<?php

declare(strict_types=1);

namespace App\Service\Registration;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class Register
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher, private readonly EntityManagerInterface $manager)
    {
    }

    public function register(PasswordAuthenticatedUserInterface $registeredUser, string $plainPassword): void
    {
        $hashedPassword = $this->hasher->hashPassword($registeredUser, $plainPassword);
        $registeredUser
            ->setDisabled(false)
            ->setVerified(false)
            ->setPassword($hashedPassword);
        $this->manager->persist($registeredUser);
        $this->manager->flush();

        // probably send the verification mail here. @see src/Service/Mailer::sendMailNotification
    }
}