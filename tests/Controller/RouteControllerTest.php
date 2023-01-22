<?php

namespace App\Tests\Controller;

use App\DataFixtures\LocationFixtures;
use App\Tests\DatabaseTestCase;
use App\Tests\DataFixtures\EventFailureFixtures;
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
    public function route_view_404_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class
        ]);

        $this->client->request('GET', '/routes/ride-the');

        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertStringContainsString('Invalid route or route collection', $response->getContent());
    }

    /**
     * @test
     */
    public function route_view_routecollection_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class
        ]);

        $this->client->request('GET', '/routes/test-route');

        $this->assertResponseIsSuccessful();

        // Check title
        $this->assertSelectorTextContains('h5', 'Test Route');
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
    public function route_fetch_scheduled_redirect_routecollection_test()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class,
            EventFailureFixtures::class
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

    /**
     * @test
     */
    public function route_gpx()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class,
            EventFixtures::class
        ]);

        $this->client->request('GET', '/routes/ride-the-rogue-5/gpx');

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $contentType = $response->headers->get('content-type');
        $contentDisposition = $response->headers->get('content-disposition');

        $this->assertEquals($contentType, 'application/gpx+xml');
        $this->assertEquals($contentDisposition, 'attachment; filename=ride-the-rogue-5.gpx');
    }

    /**
     * @test
     */
    public function route_gpx_error()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class,
            EventFixtures::class
        ]);

        $this->client->request('GET', '/routes/ride-the-5/gpx');

        $this->assertResponseStatusCodeSame(404);
    }
}