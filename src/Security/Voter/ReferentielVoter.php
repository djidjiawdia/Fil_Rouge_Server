<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ReferentielVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['REF_CREATE', 'REF_EDIT', 'REF_VIEW']);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'REF_CREATE':
                return $user->getRoles()[0] === "ROLE_ADMIN";
            break;
            case 'REF_EDIT':
                return 
                    $user->getRoles()[0] === "ROLE_ADMIN" ||
                    $user->getRoles()[0] === "ROLE_FORMATEUR";
                break;
            case 'REF_VIEW':
                return
                    $user->getRoles()[0] === "ROLE_ADMIN" ||
                    $user->getRoles()[0] === "ROLE_CM" ||
                    $user->getRoles()[0] === "ROLE_FORMATEUR";
                break;
        }

        return false;
    }
}
