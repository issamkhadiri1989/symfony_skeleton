<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegisterType;
use App\Service\Blog\BlogManager;
use App\Service\Mailer;
use App\Service\Registration\Register;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsController]
class IndexController
{
    public function __construct(private readonly Environment $twig, LoggerInterface $LoggerRequest, $router)
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
    public function index(BlogManager $blogManager): Response
    {
        $content = $this->twig->render('index/index.html.twig', [
            'blogs' => $blogManager->splitToNewlyAndExtraBlogs(5),
        ]);

        return new Response($content);
    }

    #[Route(path: "/register", name: "app_register")]
    public function register(
        FormFactoryInterface $formFactory,
        Request              $request,
        Register             $register,
        RouterInterface      $router
    ): Response
    {
        $user = new User();
        $form = $formFactory->create(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form
                ->get('registerPassword')
                ->getData();
            $register->register($user, $plainPassword);

            return $this->doNotifyOnSuccessAndBuildTargetDestination(
                $router,
                $request,
                ['route_name' => 'app_index', 'email_address' => $user->getEmail()]
            );
        }

        return new Response($this->twig->render(
            'login/register.html.twig',
            ['registerForm' => $form->createView()]
        ));
    }

    /**
     * Show a success notification to the end user and builds a redirection response.
     *
     * @param RouterInterface $router
     * @param Request $request
     * @param array $parameters
     *
     * @return RedirectResponse
     */
    private function doNotifyOnSuccessAndBuildTargetDestination(
        RouterInterface $router,
        Request         $request,
        array           $parameters = []
    ): RedirectResponse
    {
        $request->getSession()->getFlashBag()->add(
            'success',
            \sprintf(
                'Registration successed. An email has been sent to `%s`. Please verify your email before continue',
                $parameters['email_address']
            ));

        $target = $this->doBuilderResponse($router, $parameters['route_name'], $parameters['route_parameters']);

        return new RedirectResponse($target);
    }

    /**
     * A simple function that create a really simple RedirectResponse instance with minimum options.
     *
     * @param RouterInterface $router
     * @param string $routeName
     * @param array $parameters
     *
     * @return string
     */
    private function doBuilderResponse(RouterInterface $router, string $routeName, array $parameters = []): string
    {
        return $router->generate(
            name: $routeName,
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
            parameters: $parameters
        );
    }
}
