<?php

namespace App\Service\Category;

use App\Repository\CategoryRepository;
use App\Service\AbstractSplitter;

class CategoryManager
{
    public function __construct(private readonly CategoryRepository $repository)
    {
    }

    public function splitCategories(): object
    {
        return new class ($this->repository) extends AbstractSplitter {
            protected function dataBuilder(): array
            {
                return $this->repository->getLightCategories();
            }
        };
    }
}