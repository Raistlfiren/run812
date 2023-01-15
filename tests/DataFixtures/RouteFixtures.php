<?php

namespace App\Tests\DataFixtures;

use App\Entity\Route;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RouteFixtures extends Fixture
{
    public const TEST_ROUTE_1 = 'test_route_1';
    public const TEST_ROUTE_2 = 'test_route_2';
    public function load(ObjectManager $manager): void
    {
        $json = file_get_contents(__DIR__ . '/../Responses/Expected/fetch_individual_route.json');

        $route1 = new Route();
        $route1->setId(1123456);
        $route1->setName('Test Route');
        $route1->setDescription('Test Route');
        $route1->setDistance(5);
        $route1->setElevationLoss(100);
        $route1->setElevationGain(500);
        $route1->setTrackType('out and back');
        $route1->setJsonRoute(json_decode($json, true));

        $manager->persist($route1);

        $route2 = new Route();
        $route2->setId(1123457);
        $route2->setName('Test 2 Route');
        $route2->setDescription('Test 2 Route');
        $route2->setDistance(5);
        $route2->setElevationLoss(100);
        $route2->setElevationGain(500);
        $route2->setTrackType('out and back');
        $route2->setJsonRoute(json_decode($json, true));

        $manager->persist($route2);

        $data = json_decode($json);

        $route = new Route();
        $route->setId($data->route->id);
        $route->setName($data->route->name);
        $route->setDescription($data->route->name);
        $route->setDistance(5);
        $route->setElevationLoss(100);
        $route->setElevationGain(500);
        $route->setTrackType('out and back');
        $route->setJsonRoute(json_decode($json, true));

        $manager->persist($route);

        $manager->flush();

        $this->addReference(self::TEST_ROUTE_1, $route1);
        $this->addReference(self::TEST_ROUTE_2, $route2);
    }
}
