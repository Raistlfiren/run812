<?php

namespace App\Tests\Controller;

use App\Tests\DatabaseTestCase;

class SecurityControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function login_test()
    {

        $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @test
     */
    public function logout_test()
    {

        $this->client->request('GET', '/logout');

        $this->assertResponseStatusCodeSame(302);
    }
}