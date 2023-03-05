<?php

declare(strict_types=1);

namespace App\Service\Blog;

use App\Entity\Blog;
use App\Entity\User;
use App\Repository\BlogRepository;
use App\Service\AbstractSplitter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class BlogManager
{
    public function __construct(private readonly BlogRepository $repository)
    {
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
        return new class ($this->repository, $maxNumber) extends AbstractSplitter {
            private array $blogs;
            private bool $empty;

            public function __construct(ServiceEntityRepository $repository, int $maxNumber)
            {
                parent::__construct($repository);
                $this->blogs = $repository->findBy(
                    criteria: ['draft' => false],
                    limit: $maxNumber,
                    orderBy: ['publishDate' => 'DESC']
                );
                $this->empty = empty($this->blogs) === true;
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

            protected function dataBuilder(): array
            {
                if (true === $this->empty || \count($this->blogs) === 1) {
                    return [];
                }
                // get the rest of the list except the first one
                return \array_slice($this->blogs, 1);
            }
        };
    }

    /**
     * Removes all blogs associated to the given author.
     *
     * @param User $author
     * @param bool $doFlush
     *
     * @return void
     */
    public function removeRelatedBlogs(User $author, bool $doFlush = false): void
    {
        $this->repository->removeEntries($author->getBlogs(), $doFlush);
    }
}
