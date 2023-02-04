<?php

declare(strict_types=1);

namespace App\Form\Transformer;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SlugToCategoryTransformer implements DataTransformerInterface
{
    public function __construct(private readonly CategoryRepository $repository)
    {
    }

    public function transform(mixed $value)
    {
        if (!$value instanceof Category) {
            return '';
        }

        return $value->getSlug();
    }

    public function reverseTransform(mixed $value)
    {
        if (!$value) {
            return null;
        }

        /** @var Category $category */
        $category = $this->repository->findOneBy(['slug' => $value]);
        if (null === $category) {
            $internalErrorMessage = \sprintf('Transformation of %s into %s failed', $value, Category::class);
            $exception = new TransformationFailedException($internalErrorMessage);
            $userErrorMessage = 'No category instance was found with the given slug `{{ value }}`';
            $exception->setInvalidMessage($userErrorMessage, ['{{ value }}' => $value]);

            throw $exception;
        }

        return $category;
    }
}