<?php

namespace App\Tests;

class UserTest extends AbstractEndPoint
{
    public function testGetUsers()
    {
        $client = $this->createAuthenticatedClient("admin2@test.com", "admin");

        $client->request(
            "POST",
            "admin/users"
        );

        $this->assertResponseIsSuccessful();
    }

}