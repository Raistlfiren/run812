<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Location;
use App\Entity\RouteCollection;
use App\Service\RideWithGPSClient;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(LocationCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    #[Route('/admin/fetch/routes', name: 'admin_fetch_routes')]
    public function fetchRoutes(RideWithGPSClient $rideWithGPSClient, SessionInterface $session): Response
    {
        $rideWithGPSClient->fetchRoutes();

        $this->addFlash('success', 'Routes updated!');

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(LocationCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end users
            ->setTitle('Dashboard')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
//            ->renderSidebarMinimized()

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Route'),
            MenuItem::linkToCrud('Location', 'fa fa-map', Location::class),

            MenuItem::section('Route Collection'),
            MenuItem::linkToCrud('Route Collection', 'fa fa-list', RouteCollection::class),
            MenuItem::linkToCrud('Events', 'fa fa-calendar', Event::class),

            MenuItem::section('Ride With GPS'),
            MenuItem::linkToRoute('Fetch Latest Routes', 'fa fa-download', 'admin_fetch_routes'),

            MenuItem::section('Profile'),
            MenuItem::linkToLogout('Logout', 'fa fa-right-from-bracket'),
        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
