<?php

namespace App\Tests\Service;

use App\Entity\Route;
use App\Http\RouteClient\RouteClientInterface;
use App\Service\RouteHandler;
use App\Tests\DatabaseTestCase;
use Symfony\Component\Filesystem\Filesystem;

class RouteHandlerTest extends DatabaseTestCase
{

    public function test_route_handler()
    {
        $fellAllRoutes = file_get_contents(__DIR__ . '/../Responses/Expected/fetch_all_routes.json');
        $fetchRoute = file_get_contents(__DIR__ . '/../Responses/Expected/fetch_individual_route.json');
        $routeThumbnail = file_get_contents(__DIR__ . '/../Responses/Expected/fetch_thumbnail.png');

        $routeClient = $this->createMock(RouteClientInterface::class);
        $filesystem = $this->createMock(Filesystem::class);
        $routeHandler = $this->getMockBuilder(RouteHandler::class)
            ->onlyMethods(['truncateRoutesTable', 'convertFileToWebP'])
            ->setConstructorArgs([$routeClient, $this->entityManager, '', $filesystem])
            ->getMock();

        $routeHandler->expects($this->once())
            ->method('truncateRoutesTable');

        $routeHandler->expects($this->exactly(2))
            ->method('convertFileToWebP');

        $filesystem->expects($this->once())
            ->method('remove');

        $filesystem->expects($this->exactly(2))
            ->method('dumpFile');

        $routeClient->expects($this->once())
            ->method('fetchAllRoutes')
            ->willReturn(json_decode($fellAllRoutes, true)['results']);

        $routeClient->expects($this->exactly(2))
            ->method('fetchRoute')
            ->willReturn(json_decode($fetchRoute, true));

        $routeClient->expects($this->exactly(2))
            ->method('fetchThumbnail')
            ->willReturn($routeThumbnail);

        $routeHandler->fetchRoutes();

        $routes = $this->entityManager->getRepository(Route::class)->findAll();
        $this->assertCount(2, $routes);
    }
}