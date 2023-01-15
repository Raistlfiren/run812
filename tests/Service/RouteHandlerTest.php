<?php

namespace App\Tests\Service;

use App\Entity\Route;
use App\Http\RouteClient\RouteClientInterface;
use App\Service\RouteHandler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class RouteHandlerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->initDatabase($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')->getManager();
    }

    private function initDatabase(KernelInterface $kernel): void
    {
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }


    public function truncate_routes_table()
    {
        $this->markTestSkipped();
        $routeClient = $this->getMockClass(RouteClientInterface::class);
        $fileSystem = $this->getMockClass(Filesystem::class);

        $routeHandler = new RouteHandler($routeClient, $this->entityManager, '', $fileSystem);

        $routeHandler->truncateRoutesTable();

        $route = new Route();
        $route->setId(1123456);
        $route->setName('Test Route');
        $route->setDistance(5);
        $route->setElevationLoss(100);
        $route->setElevationGain(500);
        $route->setTrackType('out and back');
        $route->setJsonRoute(json_encode(['type' => 'route']));

        $this->entityManager->persist($route);
        $this->entityManager->flush();

        $test = $this->entityManager->getRepository(Route::class)->findAll();

        $routeClient = $this->getMockClass(RouteClientInterface::class);
        $fileSystem = $this->getMockClass(Filesystem::class);

        $routeHandler = new RouteHandler($routeClient, $this->entityManager, '', $fileSystem);

        $routeHandler->truncateRoutesTable();

        $test = $this->entityManager->getRepository(Route::class)->findAll();
    }
}