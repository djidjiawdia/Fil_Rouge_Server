<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CompetenceVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        // dd($subject);
        return in_array($attribute, ['COMPETENCE_VIEW']);
           // && $subject instanceof \App\Entity\Competence;
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
            case 'COMPETENCE_VIEW':
                // logic to determine if the user can VIEW
                return 
                    $user->getRoles()[0] == "ROLE_ADMIN" ||
                    $user->getRoles()[0] == "ROLE_FORMATEUR" ||
                    $user->getRoles()[0] == "ROLE_CM"
                ;
                break;
        }

        return false;
    }
}
