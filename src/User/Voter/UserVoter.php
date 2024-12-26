<?php

namespace App\User\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const SHOW = 'show';
    const CREATE = 'create';
    const DELETE = 'delete';

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

        if  (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var User $user */
        $user = $subject;

        return match($attribute) {
            self::CREATE => true,
            self::DELETE => $this->canDelete($user, $token->getUser()),
            default => throw new \LogicException('This code should not be reached!')
        };
    }
    private function canEdit(User $user, UserInterface $userInterface): bool
    {

        if (!$user) {
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
        return $user->getUser() === $userInterface;
    }

    private function canDelete(User $user, UserInterface $userInterface): bool{
        if (!$user) {
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
        return $user->getUser() === $userInterface;
    }
}
