<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User as UserEntity;
use App\Model\PasswordHolder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This service can be much more efficient and can handle more things like generating forms.
 */
class User
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function updateUser(UserInterface $user): void
    {
        $this->manager->persist($user);
        $this->manager->flush();
    }

    public function editPassword(PasswordAuthenticatedUserInterface $user, PasswordHolder $passwordHolder): void
    {
        $hashedPassword = $this->hasher->hashPassword($user, $passwordHolder->getNewPassword());
        if ($user instanceof UserEntity) {
            $user->setPassword($hashedPassword);
        }
        $this->manager->flush();
    }

    /**
     * Performs the updating of the connection date and time.
     *
     * @param UserEntity $user
     *
     * \@return void
     */
    public function updateLoginDate(UserEntity $user): void
    {
        $user->setLastConnectionDate(new \DateTimeImmutable());
        $this->manager->flush();
    }
}