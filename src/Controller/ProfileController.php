<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\EditPasswordType;
use App\Form\Type\ProfileType;
use App\Model\PasswordHolder;
use App\Service\Security\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;

#[AsController]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProfileController
{
    public function __construct(
        private readonly Security $security,
        private readonly FormFactoryInterface $factory,
        private readonly Environment $environment
    ) {
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, User $manager, UrlGeneratorInterface $generator): Response
    {
        // @TODO rewrite this action it seems that it starts having lot of code :)

        $currentUser = $this->security->getUser();
        $form = $this->factory->create(ProfileType::class, $currentUser);
        $changePasswordHolder = new PasswordHolder();
        $editPassword = $this->factory->create(EditPasswordType::class, $changePasswordHolder);

        $form->handleRequest($request);
        $editPassword->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->updateUser($currentUser);

            return new RedirectResponse($generator->generate('app_profile'));
        }

        if ($editPassword->isSubmitted()  && $editPassword->isValid()) {
            $manager->editPassword($currentUser, $changePasswordHolder);
            $this->security->logout(false);

            return new RedirectResponse($generator->generate('app_index'));
        }

        return new Response($this->environment->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'form' => $form->createView(),
            'edit_password' => $editPassword->createView(),
        ]));
    }
}
