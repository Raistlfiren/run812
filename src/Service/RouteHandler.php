<?php

namespace App\Service;

use App\Entity\Route;
use App\Http\RouteClient\RouteClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Service to pull the latest routes from Ride with GPS and put them into the database.
 */
class RouteHandler
{
    private EntityManagerInterface $em;
    private $routesDirectory;
    private Filesystem $filesystem;
    private RouteClientInterface $routeClient;

    public function __construct(
        RouteClientInterface $routeClient,
        EntityManagerInterface $em,
        $routesDirectory,
        Filesystem $filesystem
    )
    {
        $this->em = $em;
        $this->routesDirectory = $routesDirectory;
        $this->filesystem = $filesystem;
        $this->routeClient = $routeClient;
    }

    protected function truncateRoutesTable()
    {
        $connection = $this->em->getConnection();
        $connection->prepare('SET FOREIGN_KEY_CHECKS=0')->executeQuery();
        $connection->prepare('TRUNCATE TABLE route')->executeQuery();
        $connection->prepare('SET FOREIGN_KEY_CHECKS=1')->executeQuery();
    }

    // Test - does it truncate routes table
    // Test - does it delete all files in routes directory
    // Test - does an object get created with specified data
    // Test - does a thumbnail get stored in the filesystem
    // Test - does a WEBP file get created and stored in the filesystem
    // Test - does the database contain a new route
    public function fetchRoutes()
    {
        $this->truncateRoutesTable();

        // Remove all png and webp images that are downloaded from Ride with GPS
        $this->filesystem->remove(glob($this->routesDirectory . DIRECTORY_SEPARATOR . '*'));

        // Generate a unique token to download routes individually
        // $this->routeClient->authenticate();

        foreach ($this->routeClient->fetchAllRoutes() as $route) {
            $name = $route['name'];
            $rideWithGPSID = $route['id'];

            // Create a new route in the database and store route metadata
            $routeEntity = new Route();
            $distance = MeterToMileConverter::convertToMiles($route['distance']);
            $routeEntity->setName($name);
            $routeEntity->setDescription($route['description']);
            $routeEntity->setDistance($distance);
            $routeEntity->setElevationGain($route['elevation_gain']);
            $routeEntity->setElevationLoss($route['elevation_loss']);
            $routeEntity->setTrackType($route['track_type']);
            $routeEntity->setId($rideWithGPSID);

            $individualRoute = $this->routeClient->fetchRoute($rideWithGPSID);

            $routeEntity->setJsonRoute($individualRoute);

            $this->em->persist($routeEntity);

            $thumbnailImage = $this->routeClient->fetchThumbnail($rideWithGPSID);

            $filePath = $this->routesDirectory . DIRECTORY_SEPARATOR . $rideWithGPSID . '.png';
            $webPFilePath = $this->routesDirectory . DIRECTORY_SEPARATOR . $rideWithGPSID . '.webp';
            $this->filesystem->dumpFile($filePath, $thumbnailImage);

            $this->convertFileToWebP($filePath, $webPFilePath);
        }

        $this->em->flush();

    }

    protected function convertFileToWebP($filePath, $webPFilePath)
    {
        $gdImageInstance = imagecreatefrompng($filePath);

        // Convert the PNG to WEBP
        imagewebp(
            $gdImageInstance,
            $webPFilePath,
            100
        );

        imagedestroy($gdImageInstance);
    }
}