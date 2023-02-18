<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Service\Category\CategoryManager;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
     * @param Category $category
     *
     * @return array
     */
    #[Route(path: "/category/{slug}", name: "view_category")]
    #[Template('category/show.html.twig')]
    public function listBlogsByGivenCategory(#[MapEntity(mapping: ["slug" => "slug"])] Category $category): array
    {
        return ['category' => $category];
    }

    public function retrieveAllCategories(CategoryManager $manager): Response
    {
        $categories = $manager->splitCategories();

        return new Response($this->twig->render(
            'category/list.html.twig',
            ['categories' => $categories]
        ));
    }
}
