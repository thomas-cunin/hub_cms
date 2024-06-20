<?php

namespace App\Controller\Manage;

use App\Entity\App;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppDashboardController extends AbstractController
{
    #[Route('/{appId}/dashboard', name: 'app_dashboard')]

    public function index(
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App $app
    ): Response
    {
        return $this->render('app_dashboard/index.html.twig', [
            'app' => $app,

        ]);
    }
}
