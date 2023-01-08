<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Route as EntityRoute;
use App\Entity\RouteCollection;
use App\Repository\EventRepository;
use App\Repository\RouteCollectionRepository;
use App\Repository\RouteRepository;
use App\Service\GeoJsonConverter;
use Mpdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/routes', name: 'route_')]
class RouteController extends AbstractController
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

    #[Route('/{slug}/geojson', name: 'geojson')]
    public function fetchGeoJSON(Request $request, EntityRoute $route)
    {
        return $this->json([
            'slug'  => $route->getSlug(),
            'geojson' => GeoJsonConverter::convertRoute($route)
        ]);
    }

    #[Route('/scheduled', name: 'scheduled')]
    #[Template('route/view.html.twig')]
    public function scheduled(Request $request, EventRepository $eventRepository)
    {
        $route = null;
        /** @var Event $scheduledRoute */
        $scheduledRoute = $eventRepository->findLatestRoute();

        if ($scheduledRoute) {
            if ($scheduledRoute->getRouteCollection() === null) {
                return $this->redirectToRoute('home');
            }

            $route = $scheduledRoute->getRouteCollection()->getRoutes()[0];
        }

        if (empty($route)) {
            return $this->redirectToRoute('home');
        }

        return [
            'route' => $route,
            'scheduledRoute' => $scheduledRoute
        ];
    }

    #[Route('/{slug}', name: 'view')]
    #[Template]
    public function view(Request $request, $slug)
    {
        $route = $this->routeRepository->findOneBy(['slug' => $slug]);

        if (empty($route)) {
            /** @var RouteCollection $routeCollection */
            $routeCollection = $this->routeCollectionRepository->findOneBy(['slug' => $slug]);
            if ($routeCollection) {
                $route = $routeCollection->getRoutes()[0];
            }
        }

        if (empty($route)) {
            throw $this->createNotFoundException('Invalid route or route collection');
        }

        return [
            'route' => $route,
        ];
    }

    #[Route('/{slug}/pdf', name: 'pdf')]
    #[Template()]
    public function pdf(EntityRoute $route)
    {
        $pdfHTML = $this->renderView('route/pdf.html.twig', [
            'route' => $route->getJsonRoute()['route']
        ]);

        $name = $route->getSlug().'.pdf';

        $mpdf = new Mpdf\Mpdf();
        $mpdf->WriteHTML($pdfHTML);
        $mpdf->Output($name, Mpdf\Output\Destination::INLINE);

        return [
            'route' => $route->getJsonRoute()
        ];
    }
}