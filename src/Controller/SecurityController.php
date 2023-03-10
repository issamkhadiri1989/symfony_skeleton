<?php
/**
 * This class is to use if you enable the form_login option.
 */
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// the prefix here is added to not conflit with the on in the LoginController
#[Route(path: "/security")]
class SecurityController extends AbstractController
{
    public function __construct(private readonly AuthenticationUtils $authenticationUtils)
    {
    }

    #[Route(path: "/login", name: "my_security_login")]
    public function login(): Response
    {
        // Get the last entered username.
        $lastUsername = $this->authenticationUtils->getLastUsername();
        // Get the last authentication Error if any.
        $lastError = $this->authenticationUtils->getLastAuthenticationError();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'last_error'  => $lastError,
        ]);
    }

    #[Route(path: "/logout", name: "my_security_logout")]
    public function logout()
    {
        // this action is blank and it's used to logout
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
