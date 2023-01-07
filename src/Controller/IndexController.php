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

#[AsController]
class IndexController
{
    public function __construct(private readonly Environment $twig)
    {
    }

    /**
     * Home page action.
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $content =  $this->twig->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);

        return new Response($content);
    }
}
