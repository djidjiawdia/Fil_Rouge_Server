<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FormateurVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['FORMATEUR_EDIT', 'FORMATEUR_VIEW'])
            && $subject instanceof \App\Entity\Formateur;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'FORMATEUR_EDIT':
                // logic to determine if the user can EDIT
                return
                    $user->getRoles()[0] == "ROLE_ADMIN" ||
                    $user->getId() == $subject->getId()
                ;
                break;
            case 'FORMATEUR_VIEW':
                // logic to determine if the user can VIEW
                return 
                    $user->getRoles()[0] == "ROLE_ADMIN" || 
                    $user->getRoles()[0] == "ROLE_CM" || 
                    $user->getId() == $subject->getId()
                ;
                break;
        }

        return false;
    }
}
