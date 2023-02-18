<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Blog;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BlogVoter extends Voter
{
    public const EDIT_BLOG = 'EDIT_BLOG';
    public const READ_BLOG = 'READ_BLOG';

    public function __construct(private readonly Security $security)
    {
    }

    public function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::EDIT_BLOG, self::READ_BLOG]) && $subject instanceof Blog;
    }

    public function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        // check first that the use is actually logged in
        if (null === $user) {
            return false;
        }

        return match (true) {
            self::READ_BLOG === $attribute => $this->userCanReadBlog($subject, $user),
            self::EDIT_BLOG === $attribute => $this->userCanEditBlog($subject, $user),
            // let's give ROLE_ADMIN the ultimate power :)
            default  => $this->security->isGranted('ROLE_ADMIN') === true
        };
    }

    private function userCanEditBlog(Blog $blog, User $author): bool
    {
        //only the current user is capable to edit.
        return $blog->getAuthor() === $author;
    }

    private function userCanReadBlog(Blog $blog, User $author): bool
    {
        if ($this->userCanEditBlog($blog, $author) === true) { // The current user is the author
            return true;
        }

        return $blog->isDraft() === false;
    }
}
