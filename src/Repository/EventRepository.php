<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findLatestRoute()
    {
        // Show the route for an additional 2 hours after the event
        $dateTime = (new \DateTime())
            ->modify('-2 hours')
            ->format('Y-m-d H:i:s');

        $results = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.datetime > :max')
                ->setParameter('max', $dateTime)
            ->getQuery()
            ->getResult();

        return $results ? $results[0] : null;
    }
}