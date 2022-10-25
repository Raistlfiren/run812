<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Repository\RouteRepository;
use App\Service\GeoJsonConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private RouteRepository $routeRepository;

    private GeoJsonConverter $geoJsonConverter;

    public function __construct(RouteRepository $routeRepository, GeoJsonConverter $geoJsonConverter)
    {
        $this->routeRepository = $routeRepository;
        $this->geoJsonConverter = $geoJsonConverter;
    }

    #[Route('/', name: 'home')]
    #[Template]
    public function index(Request $request, LocationRepository $locationRepository)
    {
        $locations = $locationRepository->findAll();
        $routes = $this->routeRepository->findAllWithCollection();
        $minDistance = $this->routeRepository->findMinimumDistance();
        $maxDistance = $this->routeRepository->findMaximumDistance();

        return [
            'routes' => $routes,
            'locations' => $locations,
            'minDistance' => $minDistance,
            'maxdistance' => $maxDistance
        ];
    }
}