<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\LocationRepository;
use App\Repository\RouteCollectionRepository;
use App\Repository\RouteRepository;
use App\Repository\RunningGroupRepository;
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
    #[Template]
    public function index(
        Request $request,
        LocationRepository $locationRepository,
        EventRepository $eventRepository,
        RunningGroupRepository $runningGroupRepository
    )
    {
        $scheduledRoute = $eventRepository->findLatestRoute();
        $locations = $locationRepository->findBy([], ['title' => 'asc']);
        $runningGroups = $runningGroupRepository->findBy([], ['title' => 'asc']);
        $routes = $this->routeRepository->findAllWithCollection();
        $minDistance = $this->routeRepository->findMinimumDistance();
        $maxDistance = $this->routeRepository->findMaximumDistance();

        return [
            'routes' => $routes,
            'locations' => $locations,
            'runningGroups' => $runningGroups,
            'minDistance' => $minDistance,
            'maxdistance' => $maxDistance,
            'scheduledRoute' => $scheduledRoute
        ];
    }
}