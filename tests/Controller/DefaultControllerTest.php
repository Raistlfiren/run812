<?php

namespace App\Tests\Controller;

use App\DataFixtures\LocationFixtures;
use App\Tests\DatabaseTestCase;
use App\Tests\DataFixtures\RouteCollectionFixtures;
use App\Tests\DataFixtures\RouteFixtures;

class DefaultControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function home_route_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class
        ]);

        $crawler = $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        // Check for routes
        $this->assertCount(2, $crawler->filter('.grid-item'));

        //Check for locations
        $this->assertCount(5, $crawler->filter('.form-check-input'));
    }
}