<?php

namespace App\Controller;

use App\Entity\RouteCollection;
use App\Repository\RouteCollectionRepository;
use App\Repository\RouteRepository;
use App\Service\GeoJsonConverter;
use Mpdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/route', name: 'route_')]
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
    public function fetchGeoJSON(Request $request, $slug)
    {
        $route = $this->routeRepository->findOneBy(['slug' => $slug]);

        return $this->json([
            'slug'  => $slug,
            'geojson' => GeoJsonConverter::convertRoute($route)
        ]);
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

        return [
            'route' => $route,
        ];
    }

    #[Route('/{slug}/pdf', name: 'pdf')]
    #[Template('default/pdf.html.twig')]
    public function pdf(RouteRepository $routeRepository, $slug)
    {
        $route = $routeRepository->findOneBy(['slug' => $slug]);

        $pdfHTML = $this->renderView('default/pdf.html.twig', [
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