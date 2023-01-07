<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * This controller class is meant to manage everything related to categories.
 */
#[AsController]
class CategoryController
{
    public function __construct(private readonly Environment $twig)
    {
    }

    /**
     * Shows all blogs that belongs to a given category.
     *
     * @param string $slug
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[Route(path: "/category/{slug}", name: "view_category")]
    public function listBlogsByGivenCategory(string $slug): Response
    {
        $content =  $this->twig->render('category/view.html.twig');

        return new Response($content);
    }
}
