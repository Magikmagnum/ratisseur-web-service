<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ExperiencesVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Experiences;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // check if the subject (Identite entity) has a user association
        $experiencesUser = $subject->getUser();

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $user === $experiencesUser;
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return $user === $experiencesUser || $this->isGranted('ROLE_ADMIN', $user);
                break;

            case self::DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                return $user === $experiencesUser || $this->isGranted('ROLE_ADMIN', $user);
                break;
        }

        return false;
    }
}
