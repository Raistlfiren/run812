<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LocationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $value) {
            $location = new Location();
            $location->setTitle($value);

            $manager->persist($location);
        }

        $manager->flush();
    }

    public function getData()
    {
        return [
            'Eastside',
            'Westside',
            'Northside',
            'Downtown',
            'Newburgh'
        ];
    }
}
