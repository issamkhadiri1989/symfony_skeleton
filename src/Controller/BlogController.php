<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Blog;
use App\Service\Blog\FormProcessor;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This controller class manages everything related to blogs.
 */
class BlogController extends  AbstractController
{
    public function __construct(private readonly FormProcessor $processor)
    {
    }

    /**
     * This action is meant to show details about a blog.
     *
     * @param Blog $blog
     *
     * @return array
     */
    #[Route(path: "/blog/{slug}", name: "view_blog", priority: 1)]
    #[Template("blog/view.html.twig")]
    public function singleBlock(#[MapEntity(mapping: ['slug' => 'slug'])] Blog $blog): array
    {
        return ['blog' => $blog];
    }

    #[Route(path: "/blog/new", name: "new_blog", priority: 2)]
    public function newBlog(): Response
    {
        $blog = new Blog();
        $blog->setCreationDate(new \DateTimeImmutable());
        $form = $this->processor->formBuilder($blog);
        if ($this->processor->processForm($form, $blog, true) === true) {
            return $this->redirectToRoute('new_blog');
        }

        return $this->render('blog/new.html.twig', ['form' => $form]);
    }

    #[Route(path: "/blog/edit/{id}", name: "edit_blog")]
    #[Template(template: "blog/new.html.twig")]
    public function edit(Blog $blog): array|RedirectResponse
    {
        $form = $this->processor->formBuilder($blog);
        if ($this->processor->processForm($form, $blog) === true) {
            return $this->redirectToRoute('edit_blog', ['id' => $blog->getId()]);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
