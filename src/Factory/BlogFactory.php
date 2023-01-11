<?php

namespace App\Factory;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Blog>
 *
 * @method        Blog|Proxy create(array|callable $attributes = [])
 * @method static Blog|Proxy createOne(array $attributes = [])
 * @method static Blog|Proxy find(object|array|mixed $criteria)
 * @method static Blog|Proxy findOrCreate(array $attributes)
 * @method static Blog|Proxy first(string $sortedField = 'id')
 * @method static Blog|Proxy last(string $sortedField = 'id')
 * @method static Blog|Proxy random(array $attributes = [])
 * @method static Blog|Proxy randomOrCreate(array $attributes = [])
 * @method static BlogRepository|RepositoryProxy repository()
 * @method static Blog[]|Proxy[] all()
 * @method static Blog[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Blog[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Blog[]|Proxy[] findBy(array $attributes)
 * @method static Blog[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Blog[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class BlogFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $title = self::faker()->text(255);
        $htmlContent = '<p>'.implode('</p><p>', self::faker()->paragraphs(\rand(9, 20)))."</p>";

        return [
            'content' => $htmlContent,
            'creationDate' => self::faker()->dateTimeThisMonth('now'),
            'draft' => self::faker()->boolean(),
            'publishDate' => self::faker()->dateTimeBetween('-1 day', '+ 1 day'),
            'title' => $title,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Blog $blog): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Blog::class;
    }
}
