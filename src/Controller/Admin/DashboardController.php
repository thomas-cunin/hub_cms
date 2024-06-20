<?php

namespace App\Controller\Admin;

use App\Entity\App;
use App\Repository\AppRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;


class DashboardController extends AbstractDashboardController
{
    public function __construct( public ChartBuilderInterface $chartBuilder,public AppRepository $appRepository, public RequestStack $requestStack,public UserRepository $userRepository)
    {
    }

    public function configureFilters(): Filters
    {

        return parent::configureFilters();
    }

    #[Route('{appId}/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/dashboard.html.twig', [
        ]);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hub Cms');
    }

    public function configureMenuItems(): iterable
    {
        $appId = $this->requestStack->getCurrentRequest()->attributes->get('appId');
        $appCount = $this->appRepository->count([]);
        $usersCount = $this->userRepository->count([]);
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class)->setBadge($usersCount);
        yield MenuItem::linkToCrud('Apps', 'fa fa-user', App::class)->setBadge($appCount);
        yield MenuItem::linkToRoute('dashboard manage', 'fa fa-home', 'app_dashboard', ['appId' => $appId]);
        // add link to home route
    }
}
