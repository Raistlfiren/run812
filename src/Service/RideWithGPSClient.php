<?php

namespace App\Service;

use App\Entity\Route;
use App\Entity\RouteCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RideWithGPSClient
{
    private HttpClientInterface $client;
    private string $email;
    private string $password;
    private string $apiKey;
    private EntityManagerInterface $em;

    public function __construct(
        HttpClientInterface $client,
        string $rideWithGPSEmail,
        string $rideWithGPSPassword,
        string $rideWithGPSAPIKey,
        EntityManagerInterface $em
    )
    {
        $this->client = $client;
        $this->email = $rideWithGPSEmail;
        $this->password = $rideWithGPSPassword;
        $this->apiKey = $rideWithGPSAPIKey;
        $this->em = $em;
    }

    public function fetchRoutes()
    {
        $connection = $this->em->getConnection();
        $connection->prepare('SET FOREIGN_KEY_CHECKS=0')->executeQuery();
        $connection->prepare('TRUNCATE TABLE route')->executeQuery();
        $connection->prepare('TRUNCATE TABLE route_collection')->executeQuery();
        $connection->prepare('SET FOREIGN_KEY_CHECKS=1')->executeQuery();

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

        if (isset($content['user']) && isset($content['user']['auth_token'])) {
            $id = $content['user']['id'];
            $token = $content['user']['auth_token'];

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
            $routeCollection = [];

            foreach ($routes as $route) {

                $name = $route['name'];
                $rideWithGPSID = $route['id'];

                $routeEntity = new Route();
                $distance = MeterToMileConverter::convertToMiles($route['distance']);
                $routeEntity->setName($name);
                $routeEntity->setDistance($distance);
                $routeEntity->setId($rideWithGPSID);
                $routeCollections[$name][] = $routeEntity;

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
            }

            foreach ($routeCollections as $name => $routeCollection) {
                if (count($routeCollection) > 1) {
                    $routeC = new RouteCollection();
                    $routeC->setName($name);
                    foreach ($routeCollection as $route) {
                        $route->setRouteCollection($routeC);
                    }

                    $this->em->persist($routeC);
                }
            }
        }

        $this->em->flush();

    }
}