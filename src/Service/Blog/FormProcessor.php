<?php

declare(strict_types=1);

namespace App\Service\Blog;

use App\Entity\Blog;
use App\Form\Type\BlogType;
use App\Repository\BlogRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FormProcessor
{
    private Request $request;

    private SessionInterface $session;

    public function __construct(
        private readonly FormFactoryInterface $factory,
        private readonly BlogRepository $repository,
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $requestStack->getSession();
    }

    public function formBuilder(Blog $entity, array $options = []): FormInterface
    {
        $form = $this->factory->create(BlogType::class, $entity, $options);
        $form->handleRequest($this->request);

        return $form;
    }

    public function processForm(FormInterface $form, Blog $entity, bool $isNew = false): bool
    {
        if ($isProcessed = ($form->isSubmitted() && $form->isValid())) {
            $this->repository->saveEntity($entity, $isNew);
            if (true === $isNew) {
                $message = \sprintf(
                    'A new blog with id `%d` has been added.',
                    $entity->getId()
                );
            } else {
                $message = \sprintf(
                    'The blog with id `%d` has been updated.',
                    $entity->getId()
                );
            }
            // flashes are temporary messages that help user to understand what just happened.
            // they vanish once page loaded
            $this->session->getFlashBag()->add('success', $message);
        }

        return $isProcessed;
    }
}
