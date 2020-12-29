<?php

namespace App\Events;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    /**
 * @param JWTAuthenticatedEvent $event
 *
 * @return void
 */
public function onJWTCreated(JWTCreatedEvent $event)
{
    $payload = $event->getData();

    // $payload["dds"] = "dqdqs";

    $event->setData($payload);
}

}