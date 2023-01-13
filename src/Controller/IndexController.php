<?php

declare(strict_types=1);

namespace App\Controller;

use App\Cache\Adapter\CacheAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[AsController]
class IndexController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route(path: "/", name: "home_page")]
    public function index(CacheAdapter $cacheAdapter): Response
    {
        $cacheAdapter->saveToCache('my_key', 'my_value');

        return new Response($this->twig->render('index/home_page.html.twig'));
    }

    #[Route(path: "/purge", name: "purge_cache")]
    public function clearApcuCache(): Response
    {
        \apcu_clear_cache();

        return new Response(' Cache cleared');

    }
}