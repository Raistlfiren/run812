<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\EventCrudController;
use App\Controller\Admin\RouteCrudController;
use App\DataFixtures\LocationFixtures;
use App\Entity\User;
use App\Service\RouteHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\DataFixtures\EventFixtures;
use App\Tests\DataFixtures\RouteCollectionFixtures;
use App\Tests\DataFixtures\RouteFixtures;
use App\Tests\DataFixtures\UserFixtures;

class DashboardControllerTest extends DatabaseTestCase
{
    public function testDashboard()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class,
            EventFixtures::class,
            UserFixtures::class
        ]);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@test.com']);
        $this->client->loginUser($user);

        $this->client->request('GET', '/admin');
        $this->assertResponseRedirects();

        $response = $this->client->followRedirect();
        $this->assertEquals($response->getUri(), $this->adminUrlGenerator->setController(EventCrudController::class)->generateUrl());
    }

    public function testFetchRoutes()
    {
        $this->databaseTool->loadFixtures([
            LocationFixtures::class,
            RouteFixtures::class,
            RouteCollectionFixtures::class,
            EventFixtures::class,
            UserFixtures::class
        ]);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@test.com']);
        $this->client->loginUser($user);

        $routeHandler = $this->createMock(RouteHandler::class);
        $this->client->getContainer()->set(RouteHandler::class, $routeHandler);

        $this->client->request('GET', '/admin/fetch/routes');
        $this->assertResponseRedirects();

        $response = $this->client->followRedirect();
        $url = $this->adminUrlGenerator->setController(RouteCrudController::class)->generateUrl();

        // Need to remove beginning to check if contains...
        $url = str_replace('http://localhost/', '', $url);
        $this->assertStringContainsString($url, $response->getUri());
    }
}