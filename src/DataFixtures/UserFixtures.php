<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures  extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    private const MAX_USERS = 10;

    public function load(ObjectManager $manager)
    {
        UserFactory::createMany(self::MAX_USERS);
        foreach (UserFactory::all() as $user) {
            $hashedPassword = $this->hasher->hashPassword($user->object(), $user->getPassword());
            $user->setPassword($hashedPassword);
        }
    }
}
