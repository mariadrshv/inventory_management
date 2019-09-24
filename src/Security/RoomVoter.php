<?php

namespace App\Security;

use App\Entity\Room;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class RoomVoter
 * @package App\Security
 */
class RoomVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const NEW = 'new';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::NEW]) || !$subject instanceof Room) {
            return false;
        }
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                if($subject->getProperty()->getUserId() == $user){
                    return true;
                }
                break;
            case self::EDIT:
                if($subject->getProperty()->getUserId() == $user){
                    return true;
                }
                break;
            case self::DELETE:
                if($subject->getProperty()->getUserId() == $user){
                    return true;
                }
                break;
            case self::NEW:
                if($subject->getProperty()->getUserId() == $user){
                    return true;
                }
                break;

        }
        throw new \LogicException('You have no rights for this action!');
    }
}