<?php

namespace App\Tests\Controller;

use App\DataFixtures\LocationFixtures;
use App\Tests\DatabaseTestCase;
use App\Tests\DataFixtures\EventFixtures;
use App\Tests\DataFixtures\RouteCollectionFixtures;
use App\Tests\DataFixtures\RouteFixtures;

class RouteControllerTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function route_view_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class
        ]);

        $this->client->request('GET', '/routes/ride-the-rogue-5');

        $this->assertResponseIsSuccessful();
        
        // Check title
        $this->assertSelectorTextContains('h5', 'Ride the Rogue');
    }

    /**
     * @test
     */
    public function route_fetch_geojson_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class
        ]);

        $this->client->request('GET', '/routes/ride-the-rogue-5/geojson');

        $content = $this->client->getResponse()->getContent();

        $data = json_decode($content, true);
        $this->assertResponseIsSuccessful();

        $this->assertArrayHasKey('slug', $data);
        $this->assertArrayHasKey('geojson', $data);
    }

    /**
     * @test
     */
    public function route_pdf_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class
        ]);

        $this->client->request('GET', '/routes/ride-the-rogue-5/pdf');

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $contentType = $response->headers->get('content-type');
        $contentDisposition = $response->headers->get('content-disposition');

        $this->assertEquals($contentType, 'application/pdf');
        $this->assertEquals($contentDisposition, 'inline; filename=ride-the-rogue-5.pdf');
    }

    /**
     * @test
     */
    public function route_fetch_scheduled_redirect_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class
        ]);

        $this->client->request('GET', '/routes/scheduled');

        $this->assertResponseRedirects('/', 302);
    }

    /**
     * @test
     */
    public function route_fetch_scheduled_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class,
            EventFixtures::class
        ]);

        $this->client->request('GET', '/routes/scheduled');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h5', 'Test Route');
    }
}