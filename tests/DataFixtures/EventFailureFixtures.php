<?php

namespace App\Tests\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFailureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $upcomingSaturday = (new \DateTime('now'))
            ->modify('next Saturday')->setTime(7, 0);

        $event = new Event();
        $event->setDatetime($upcomingSaturday);

        $manager->persist($event);

        $manager->flush();
    }
}
