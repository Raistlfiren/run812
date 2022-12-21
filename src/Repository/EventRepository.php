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
        $dateTime = new \DateTime();

        $results = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.datetime > :now')->setParameter('now', $dateTime)
            ->getQuery()
            ->getResult();

        return $results ? $results[0] : null;
    }
}