<?php

namespace App\Events;

use App\Entity\Apprenant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class LoginListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function onLoginSuccess(AuthenticationEvent $event) {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof Apprenant) {
            return false;
        }

        if (!$user->getStatut()){
            // dd('not connected yet');
            $user->setStatut(true);
            $this->em->persist($user);
            $this->em->flush();
        }

    }
}
