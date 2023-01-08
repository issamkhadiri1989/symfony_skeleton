<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This controller class manages everything related to blogs.
 */
class BlogController extends  AbstractController
{
    /**
     * This action is meant to show details about a blog.
     *
     * @param string $slug
     *
     * @return array
     */
    #[Route(path: "/blog/{slug}", name: "view_blog")]
    #[Template("blog/view.html.twig")]
    public function singleBlock(string $slug): array
    {
        return [
            'controller_name' => 'IndexController',
        ];
    }
}
