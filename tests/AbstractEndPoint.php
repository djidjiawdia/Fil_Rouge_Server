<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractEndPoint extends WebTestCase
{
    
    
    protected function createAuthenticatedClient(string $email, string $password): KernelBrowser
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
               "email":"'. $email .'",
               "password":"'. $password .'"
            }'
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_AUTHORIZATION', \sprintf('Bearer %s', $data['token']));
        $client->setServerParameter('CONTENT_TYPE', 'application/json');
        
        return $client;
    }
}
