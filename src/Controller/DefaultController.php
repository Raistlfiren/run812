<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Repository\RouteCollectionRepository;
use App\Repository\RouteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private RouteRepository $routeRepository;

    private RouteCollectionRepository $routeCollectionRepository;

    public function __construct(
        RouteRepository $routeRepository,
        RouteCollectionRepository $routeCollectionRepository
    )
    {
        $this->routeRepository = $routeRepository;
        $this->routeCollectionRepository = $routeCollectionRepository;
    }

    #[Route('/', name: 'home')]
    #[Route('/routes', name: 'route_index')]
    #[Template]
    public function index(Request $request, LocationRepository $locationRepository)
    {
        $closestSaturday = null;
        $saturdayRoute = null;

        if ($request->attributes->get('_route') === 'home') {
            $saturdayRoute = $this->routeCollectionRepository->findOneBy(['saturdayRoute' => true]);
            if ($saturdayRoute) {
                $closestSaturday = (new \DateTime())->modify('next Saturday');
            }
        }

        $locations = $locationRepository->findBy([], ['title' => 'asc']);
        $routes = $this->routeRepository->findAllWithCollection();
        $minDistance = $this->routeRepository->findMinimumDistance();
        $maxDistance = $this->routeRepository->findMaximumDistance();

        return [
            'routes' => $routes,
            'locations' => $locations,
            'minDistance' => $minDistance,
            'maxdistance' => $maxDistance,
            'closestSaturday' => $closestSaturday,
            'saturdayRoute' => $saturdayRoute
        ];
    }
}