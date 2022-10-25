<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function findAllWithCollection()
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.routeCollection', 'rc')
            ->orderBy('r.name');

        return $qb->getQuery()->getResult();
    }

    public function findMinimumDistance()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('MIN(r.distance) AS distance');

        return $qb->getQuery()->getResult();
    }

    public function findMaximumDistance()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('MAX(r.distance) AS distance');

        return $qb->getQuery()->getResult();
    }
}