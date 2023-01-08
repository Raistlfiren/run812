<?php

namespace App\Service;

use App\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Service to pull the latest routes from Ride with GPS and put them into the database.
 */
class RideWithGPSClient
{
    private HttpClientInterface $client;
    private string $email;
    private string $password;
    private string $apiKey;
    private EntityManagerInterface $em;
    private $routesDirectory;
    private Filesystem $filesystem;

    public function __construct(
        HttpClientInterface $client,
        string $rideWithGPSEmail,
        string $rideWithGPSPassword,
        string $rideWithGPSAPIKey,
        EntityManagerInterface $em,
        $routesDirectory,
        Filesystem $filesystem
    )
    {
        $this->client = $client;
        $this->email = $rideWithGPSEmail;
        $this->password = $rideWithGPSPassword;
        $this->apiKey = $rideWithGPSAPIKey;
        $this->em = $em;
        $this->routesDirectory = $routesDirectory;
        $this->filesystem = $filesystem;
    }

    protected function truncateRoutesTable()
    {
        $connection = $this->em->getConnection();
        $connection->prepare('SET FOREIGN_KEY_CHECKS=0')->executeQuery();
        $connection->prepare('TRUNCATE TABLE route')->executeQuery();
        $connection->prepare('SET FOREIGN_KEY_CHECKS=1')->executeQuery();
    }

    public function fetchRoutes()
    {
        $this->truncateRoutesTable();

        // Remove all png and webp images that are downloaded from Ride with GPS
        $this->filesystem->remove(glob($this->routesDirectory . DIRECTORY_SEPARATOR . '*'));

        // Generate a unique token to download routes individually
        $response = $this->client->request(
            'GET',
            'https://ridewithgps.com/users/current.json',
            [
                'query' => [
                    'email' => $this->email,
                    'password' => $this->password,
                    'apikey' => $this->apiKey
                ]
            ]
        );

        $content = $response->toArray();

        // Check to see if user token was pulled successfully
        if (isset($content['user']) && isset($content['user']['auth_token'])) {
            $id = $content['user']['id'];
            $token = $content['user']['auth_token'];

            // Fetch a list of all private routes from Ride With GPS
            $response = $this->client->request(
                'GET',
                'https://ridewithgps.com/users/'.$id.'/routes.json',
                [
                    'query' => [
                        'offset' => 0,
                        'limit' => 500,
                        'version' => 2,
                        'auth_token' => $token,
                        'apikey' => $this->apiKey
                    ]
                ]
            );

            $routes = $response->toArray()['results'];

            foreach ($routes as $route) {

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

                // Fetch the routes complete JSON to store in the database
                $response = $this->client->request(
                    'GET',
                    'https://ridewithgps.com/routes/'.$rideWithGPSID.'.json',
                    [
                        'query' => [
                            'version' => 2,
                            'auth_token' => $token,
                            'apikey' => $this->apiKey
                        ]
                    ]
                );

                $routeEntity->setJsonRoute($response->toArray());

                $this->em->persist($routeEntity);

                // Fetch the static PNG image
                $staticImageResponse = $this->client->request(
                    'GET',
                    'https://ridewithgps.com/routes/'.$rideWithGPSID.'/hover_preview.png',
                    [
                        'query' => [
                            'version' => 2,
                            'auth_token' => $token,
                            'apikey' => $this->apiKey
                        ]
                    ]
                );

                $resourceFile = $staticImageResponse->getContent();

                $filePath = $this->routesDirectory . DIRECTORY_SEPARATOR . $rideWithGPSID . '.png';
                $webPFilePath = $this->routesDirectory . DIRECTORY_SEPARATOR . $rideWithGPSID . '.webp';
                $this->filesystem->dumpFile($filePath, $resourceFile);

                $gdImageInstance = imagecreatefrompng($filePath);

                // Convert the PNG to WEBP
                $conversionSuccess = imagewebp(
                    $gdImageInstance,
                    $webPFilePath,
                    100
                );

                imagedestroy($gdImageInstance);
            }
        }

        $this->em->flush();

    }
}