<?php

namespace App\Seance\Voter;

use App\Entity\Seance;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SeanceVoter extends Voter
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
        /** @var Seance $seance */
        $seance = $subject;

        return match($attribute) {
            self::SHOW => $this->canShow($seance, $user),
            self::EDIT => $this->canEdit($seance, $user),
            self::CREATE => $this->canCreate($seance, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }
    private function canShow(Seance $seance, ?User $user): bool
    {
        // the Post object could have, for example, a method `isPrivate()`
        return true;

    }
    private function canEdit(?Seance $seance, ?User $user): bool
    {

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if (!$seance) {
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
/*        return $seance->getUser() === $user;*/
        return true;
    }

    private function canCreate(?Seance $seance,?User $user): bool
    {

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /*        if (!$seance) {
                    return true;
                }*/
        // this assumes that the Post object has a `getOwner()` method
        return true;
    }

}