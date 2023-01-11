<?php

declare(strict_types=1);

namespace App\Service\Blog;

use App\Entity\Blog;
use App\Repository\BlogRepository;

class BlogManager
{
    public function __construct(private readonly BlogRepository $repository)
    {
    }

    /**
     * Finds and gets $maxCount blogs from the database which are published (not draft).
     *
     * @param int $maxCount
     *
     * @return Blog[]
     */
    public function retrieveBlogs(int $maxCount = 5): array
    {
        return $this->repository->findBy(
            criteria: ['draft' => false],
            limit: $maxCount,
            orderBy: ['publishDate' => 'DESC']
        );
    }

    /**
     * This function splits the blogs list into a newly added one and the rest of the list.
     *
     * @param int $maxNumber
     *
     * @return object
     */
    public function splitToNewlyAndExtraBlogs(int $maxNumber): object
    {
        $blogs = $this->retrieveBlogs($maxNumber);

        return new class ($blogs) {
            private array $blogs;
            private bool $empty;

            public function __construct(array $blogs)
            {
                $this->blogs = $blogs;
                $this->empty = empty($blogs) === true;
            }

            /**
             * Gets the first blog in the list.
             *
             * @return Blog|null
             */
            public function fetchTheNewlyCreatedBlog(): ?Blog
            {
                if (false === $this->empty) {
                    return $this->blogs[0];
                }

                return null;
            }

            /**
             * Gets the rest of the blogs from the list.
             *
             * @return Blog[]
             */
            public function formatAndGetRestOfBlogs(): array
            {
                if (true === $this->empty || \count($this->blogs) === 1) {
                    return [];
                }
                // get the rest of the list except the first one
                $otherBlogs = \array_slice($this->blogs, 1);

                return \array_chunk($otherBlogs, (int) \ceil(\count($otherBlogs) / 2));
            }
        };
    }
}
