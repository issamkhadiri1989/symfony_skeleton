<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param string $slug
     *
     * @return Response
     */
    #[Route(path: "/blog/{slug}", name: "view_blog")]
    public function singleBlock(string $slug): Response
    {
        return $this->render('blog/view.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
