<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommunityManagerVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CM_EDIT', 'CM_VIEW'])
            && $subject instanceof \App\Entity\CommunityManager;
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
            case 'CM_EDIT':
                // logic to determine if the user can EDIT
                return
                    $user->getRoles()[0] == "ROLE_ADMIN" ||
                    $user->getId() == $subject->getId()
                ;
                break;
            case 'CM_VIEW':
                // logic to determine if the user can VIEW
                return 
                    $user->getRoles()[0] == "ROLE_ADMIN" || 
                    $user->getId() == $subject->getId()
                ;
                break;
        }

        return false;
    }
}
