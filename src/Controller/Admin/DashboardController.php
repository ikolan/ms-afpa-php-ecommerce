<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Visualisation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render("@EasyAdmin/page/content.html.twig");
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nom du site');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute("Return to site", "fa fa-arrow-circle-left", "home"),

            MenuItem::section("Users"),
            MenuItem::linkToCrud("Users", "fa fa-user", User::class),
            MenuItem::linkToCrud("Addresses", "fa fa-map-marker-alt", Address::class),

            MenuItem::section("Products"),
            MenuItem::linkToCrud("Products", "fa fa-cube", Product::class),
            MenuItem::linkToCrud("Categories", "fa fa-cubes", Category::class),
            MenuItem::linkToCrud("Visualisations", "fa fa-images", Visualisation::class)
        ];
    }
}
