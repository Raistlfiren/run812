<?php

namespace App\Handler;

use App\Service\GPXParser;
use Doctrine\ORM\EntityManagerInterface;

class RouteHandler
{
    private EntityManagerInterface $entityManager;
    private GPXParser $GPXParser;

    public function __construct(EntityManagerInterface $entityManager, GPXParser $GPXParser)
    {
        $this->entityManager = $entityManager;
        $this->GPXParser = $GPXParser;
    }

    public function create($name, $distance, $gpx)
    {
//        $route = new Route();
//        $route->setName($name);
//        $route->setDistance($distance);

        $route = $this->GPXParser->parse($gpx);

        $this->entityManager->persist($route);

        $this->entityManager->flush();
    }
}