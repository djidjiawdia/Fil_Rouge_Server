<?php

namespace App\Tests;

use App\Tests\AbstractEndPoint;
use Symfony\Component\HttpFoundation\Response;

class ProfilTest extends AbstractEndPoint
{
    public function testGetProfils()
    {
        $client = $this->createAuthenticatedClient("admin1@test.com", "admin");
        $client->request('GET', 'admin/profils');
        
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        //dd($response);
    }
    
    public function testCreateProfil()
    {
        $client = $this->createAuthenticatedClient("admin1@test.com", "admin");

        $client->request(
            'POST',
            'admin/profils',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "libelle":"Test profil"
            }'
        );

        $this->assertResponseIsSuccessful();

    }


    
    
}