<?php

namespace App\Tests\DataFixtures;

use App\Entity\RouteCollection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RouteCollectionFixtures extends Fixture
{
    public const TEST_ROUTE_COLLECTION = 'test_route_collection';
    public function load(ObjectManager $manager): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->setName('Test Route');
        $routeCollection->addRoute($this->getReference(RouteFixtures::TEST_ROUTE_1));
        $routeCollection->addRoute($this->getReference(RouteFixtures::TEST_ROUTE_2));

        $manager->persist($routeCollection);

        $manager->flush();

        $this->addReference(self::TEST_ROUTE_COLLECTION, $routeCollection);
    }
}
