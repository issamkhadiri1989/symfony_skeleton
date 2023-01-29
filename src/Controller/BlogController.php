<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Blog;
use App\Form\Type\BlogType;
use App\Repository\BlogRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This controller class manages everything related to blogs.
 */
class BlogController extends  AbstractController
{
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
        return [
            'blog' => $blog,
        ];
    }

    #[Route(path: "/blog/new", name: "new_blog", priority: 2)]
    public function newBlog(Request $request, BlogRepository $repository): Response
    {
        $blog = new Blog();
        $blog->setCreationDate(new \DateTimeImmutable());
        $form = $this->createForm(BlogType::class, $blog, ['is_draft' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveEntity($blog, true);
            $this->addFlash('success', \sprintf('A new blog with id `%d` has been added.', $blog->getId()));

            return $this->redirectToRoute('new_blog');
        }

        return $this->render('blog/new.html.twig', ['form' => $form]);
    }
}
