<?php

namespace App\Tests\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $upcomingSaturday = (new \DateTime('now'))
            ->modify('next Saturday')->setTime(7, 0);

        $event = new Event();
        $event->setDatetime($upcomingSaturday);
        $event->setRouteCollection($this->getReference(RouteCollectionFixtures::TEST_ROUTE_COLLECTION));

        $manager->persist($event);

        $manager->flush();
    }
}
