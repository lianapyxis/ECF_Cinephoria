<?php

namespace App\Room\Voter;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RoomVoter extends Voter
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
        /** @var Room $room */
        $room = $subject;

        return match($attribute) {
            self::SHOW => $this->canShow($room, $user),
            self::EDIT => $this->canEdit($room, $user),
            self::CREATE => $this->canCreate($room, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }
    private function canShow(Room $room, ?User $user): bool
    {
        // the Post object could have, for example, a method `isPrivate()`
        return true;

    }
    private function canEdit(?Room $room, ?User $user): bool
    {

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if (!$room) {
            return true;
        }
        return true;
    }

    private function canCreate(?Room $room,?User $user): bool
    {

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /*        if (!$room) {
                    return true;
                }*/
        // this assumes that the Post object has a `getOwner()` method
        return true;
    }

}