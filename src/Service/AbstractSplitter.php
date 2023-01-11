<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractSplitter
{
    protected readonly ServiceEntityRepository $repository;

    public function __construct(ServiceEntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSplittedData(): array
    {
        $data = $this->dataBuilder();

        return \array_chunk($data, (int) \ceil(\count($data) / 2));
    }

    abstract protected function dataBuilder(): array;
}
