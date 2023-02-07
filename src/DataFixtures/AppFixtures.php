<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Factory\BlogFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    private const MAX_CATEGORIES = 6;
    private const MAX_BLOGS = 26;

    public function load(ObjectManager $manager): void
    {
        BlogFactory::createMany(self::MAX_BLOGS);
        CategoryFactory::createMany(self::MAX_CATEGORIES);
        $occurrences = [];
        $categories = CategoryFactory::all(); // get all persisted blogs

        foreach ($categories as $category) {
            $categoryId = $category->getId();
            $iterations = \rand(0, self::MAX_BLOGS);
            for ($i = 0; $i < $iterations; $i++) {
                /** @var Blog $blog */
                $blog = BlogFactory::random()->object();
                $blogId = $blog->getId();
                if ($this->isBlogPersisted($occurrences, $categoryId, $blogId) === true) {
                    continue;
                }
                $category->addBlog($blog);
                $occurrences[$categoryId][] = $blogId;
            }
        }

        $manager->flush();
    }

    private function isBlogPersisted(array $categories, int $key, mixed $value): bool
    {
        return isset($categories[$key]) === true && \in_array($value, $categories[$key]) === true;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
