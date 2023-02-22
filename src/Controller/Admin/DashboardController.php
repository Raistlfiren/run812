<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Location;
use App\Entity\Route as RouteEntity;
use App\Entity\RouteCollection;
use App\Entity\RunningGroup;
use App\Entity\User;
use App\Service\RouteHandler;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(EventCrudController::class)->generateUrl());
    }

    #[Route('/admin/fetch/routes', name: 'admin_fetch_routes')]
    public function fetchRoutes(RouteHandler $rideWithGPSClient): Response
    {
        $rideWithGPSClient->fetchRoutes();

        $this->addFlash('success', 'Routes updated!');

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(RouteCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end users
            ->setTitle('Dashboard')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()
            ->setFaviconPath('images/favicon-32x32.png')

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
            //  ->renderSidebarMinimized()

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
            MenuItem::linkToCrud('Route', 'fa fa-route', RouteEntity::class),
            MenuItem::linkToCrud('Running Group', 'fa fa-user-group', RunningGroup::class),

            MenuItem::section('Route Collection'),
            MenuItem::linkToCrud('Route Collection', 'fa fa-list', RouteCollection::class),
            MenuItem::linkToCrud('Schedule Route', 'fa fa-calendar', Event::class),

            MenuItem::section('Ride With GPS'),
            MenuItem::linkToRoute('Fetch Latest Routes', 'fa fa-download', 'admin_fetch_routes'),

            MenuItem::section('Profile'),
            MenuItem::linkToCrud('User Management', 'fa fa-users', User::class),
            MenuItem::linkToLogout('Logout', 'fa fa-right-from-bracket'),
        ];
    }
}
