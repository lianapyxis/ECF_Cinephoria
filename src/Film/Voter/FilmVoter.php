<?php

namespace App\Film\Voter;

use App\Film\Constant\FilmStatus;
use App\Entity\Film;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FilmVoter extends Voter
{
    const SHOW = 'show';

    const EDIT = 'edit';
    const CREATE = 'create';


    public function __construct(private readonly Security $security)
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::SHOW, self::EDIT])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();



        if($this->security->isGranted('ROLE_ADMIN') OR $this->security->isGranted('ROLE_WORKER')) {
            return true;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Film $film */
        $film = $subject;

        return match($attribute) {
            self::SHOW => $this->canShow($film, $user),
            self::EDIT => $this->canEdit($film, $user),
            self::CREATE => $this->canCreate($film, $user),
/*            self::PUBLISHED => $this->isPublished($film, $user),*/
            default => throw new \LogicException('This code should not be reached!')
        };
    }
    private function canShow(Film $film, ?User $user): bool
    {
/*        if($film->getStatus() === "DRAFT" && $film->getUser() !== $user) {
            return false;
        }*/

        // the Post object could have, for example, a method `isPrivate()`
        return true;

    }
    private function canEdit(?Film $film, ?User $user): bool
    {

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if (!$film) {
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
        return $film->getUser() === $user && FilmStatus::DRAFT === $film->getStatus();
    }

    private function canCreate(?Film $film,?User $user): bool
    {

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

/*        if (!$film) {
            return true;
        }*/
        // this assumes that the Post object has a `getOwner()` method
        return true;
    }

/*    private function isPublished(Film $film, User $user)
    {
        return FilmStatus::PUBLISHED === $film->getStatus();
    }*/
}