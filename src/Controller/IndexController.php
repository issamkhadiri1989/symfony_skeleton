<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Blog\BlogManager;
use App\Service\Mailer;
use Psr\Log\LoggerInterface;
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
    public function __construct(
        private readonly Environment $twig,
        private readonly BlogManager $blogManager,
        LoggerInterface $LoggerRequest,
        $router
    ) {
    }

    /**
     * Home page action.
     *
     * @param Mailer $mailer
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
            'blogs' => $this->blogManager->splitToNewlyAndExtraBlogs(5),
        ]);

        return new Response($content);
    }
}
