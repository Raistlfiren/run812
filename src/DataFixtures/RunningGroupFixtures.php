<?php

namespace App\DataFixtures;

use App\Entity\RunningGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RunningGroupFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $value) {
            $runningGroup = new RunningGroup();
            $runningGroup->setTitle($value);

            $manager->persist($runningGroup);
        }

        $manager->flush();
    }

    public function getData()
    {
        return [
            'Run/Walk 812',
            'BOR',
            'Loop Group'
        ];
    }

    public static function getGroups(): array
    {
        return ['runningGroup'];
    }
}
