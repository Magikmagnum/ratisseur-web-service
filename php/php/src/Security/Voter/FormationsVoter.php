<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FormationsVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Formations;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // check if the subject (Identite entity) has a user association
        $formationsUser = $subject->getUser();

        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // For example, allow editing if the user owns the Identite
                return $user === $formationsUser;
            case self::VIEW:
                // logic to determine if the user can VIEW
                // For example, allow viewing if the user owns the Identite or has ROLE_ADMIN
                return $user === $formationsUser || $this->isGranted('ROLE_ADMIN', $user);
            case self::DELETE:
                // logic to determine if the user can DELETE
                // For example, allow deletion if the user owns the Identite or has ROLE_ADMIN
                return $user === $formationsUser || $this->isGranted('ROLE_ADMIN', $user);
        }

        return false;
    }
}
