<?php

namespace App\Events;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param JWTAuthenticatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        $user = $this->repo->findOneBy(["email" => $payload["username"]]);
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        $payload["avatar"] = $user->getAvatar();

        $event->setData($payload);
    }
}
