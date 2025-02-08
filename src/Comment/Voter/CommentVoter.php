<?php

namespace App\Comment\Voter;

use App\Comment\Constant\CommentStatus;
use App\Entity\Comment;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    const SHOW = 'show';
    const CREATE = 'create';
    const DELETE = 'delete';

    const PUBLISHED = 'published';

    public function __construct(private readonly Security $security)
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::SHOW, self::CREATE, self::DELETE])) {
            return false;
        }

        if (null === $subject )
            {
                return true;
            }

        if  (!$subject instanceof Comment) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if($this->security->isGranted('ROLE_WORKER')) {
            return true;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Comment $comment */
        $comment = $subject;

        return match($attribute) {
            self::CREATE => true,
            self::DELETE => $this->canDelete($comment, $token->getUser()),
            self::SHOW => $this->canShow($comment, $token->getUser()),
            self::PUBLISHED => $this->isPublished($comment),
            default => throw new \LogicException('This code should not be reached!')
        };
    }
    private function canEdit(Comment $comment, UserInterface $user): bool
    {

        if (!$comment) {
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
        return $comment->getUser() === $user;
    }

    private function canDelete(Comment $comment, UserInterface $user): bool{
        if (!$comment) {
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
        return $comment->getUser() === $user;
    }

    private function canShow(Comment $comment, UserInterface $user): bool
    {
        if($comment->getStatus() === "DRAFT" && $comment->getUser() !== $user) {
            return false;
        }

        // the Post object could have, for example, a method `isPrivate()`
        return true;

    }
    private function isPublished(Comment $comment)
    {
        return CommentStatus::PUBLISHED === $comment->getStatus();
    }
}