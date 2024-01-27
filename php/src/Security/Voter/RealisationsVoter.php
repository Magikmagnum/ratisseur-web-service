<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RealisationsVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Realisations;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // check if the subject (Identite entity) has a user association
        $realisationsUser = $subject->getCompetence()->getUser();

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                return $user === $realisationsUser;
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                return $user === $realisationsUser || $this->isGranted('ROLE_ADMIN', $user);
                break;

            case self::DELETE:
                // logic to determine if the user can DELETE
                return $user === $realisationsUser || $this->isGranted('ROLE_ADMIN', $user);
                break;
        }

        return false;
    }
}
