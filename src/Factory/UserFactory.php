<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<User>
 *
 * @method        User|Proxy create(array|callable $attributes = [])
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User[]|Proxy[] createSequence(array|callable $sequence)
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    private const PLAIN_PASSWORD = '1234';

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'disabled' => self::faker()->boolean(),
            'fullName' => \sprintf('%s %s', self::faker()->firstName(), self::faker()->lastName()),
            'username' => self::faker()->userName(),
            'verified' => self::faker()->boolean(),
            'password' => '123456',
            'lastConnectionDate' => self::faker()->dateTimeBetween('-80 days')
        ];
    }

    protected function initialize(): self
    {
        return $this->afterInstantiate(function(User $user): void {
            $hashedPassword = $this->hasher->hashPassword($user, self::PLAIN_PASSWORD);
            $user->setPassword($hashedPassword);
        });
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
