<?php

declare(strict_types=1);

namespace App\Form\Factory;

use App\Form\Factory\AbstractFactory\AbstractForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * This class is the Blog Form Factory that creates and handles forms.
 */
class BlogFormFactory implements FactoryInterface
{
    private BlogForm $blogForm;

    private FormInterface $form;

    public function __construct(BlogForm $blogForm, private readonly UrlGeneratorInterface $generator)
    {
        $this->blogForm = $blogForm;
    }

    public function createForm(mixed $data, bool $isNewEntry, array $options = []): AbstractForm
    {
        $this->form = $this->blogForm->processForm($data, $options, $isNewEntry);

        return $this->blogForm;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function createResponse(): RedirectResponse
    {
        return new RedirectResponse($this->generator->generate(
            'edit_blog',
            ['id' =>  $this->blogForm->getEntityIdentifier()]
        ));
    }
}