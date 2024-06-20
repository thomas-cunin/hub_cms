<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\App;


class HomeController extends AbstractController
{
    public function __construct(public RequestStack $requestStack)
    {
    }

    #[Route('/app/new', name: 'home')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $app = new App();

        $app->setName('My First App');

        // Vous pouvez définir d'autres propriétés ici

        $entityManager->persist($app);
        $entityManager->flush();
dd($app);
        return new Response('Nouvelle application créée avec UID : ' . $app->getUid());
    }

    #[Route('/app/{uid}', name: 'app_show')]
    public function show(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        App $app,
        EntityManagerInterface $entityManager): Response
    {

        if (!$app) {
            throw $this->createNotFoundException('Aucune application trouvée.');
        }

        return new Response('Nom de l\'application : ' . $app->getName());
    }

    #[Route('/app/{uid}/set', name: 'app_set_session')]
    public function setSession(
        #[MapEntity(mapping: ['uid' => 'uid'])]
        App $app,
        EntityManagerInterface $entityManager): Response
    {

        if (!$app) {
            throw $this->createNotFoundException('Aucune application trouvée.');
        }

        $session = $this->requestStack->getCurrentRequest()->getSession();
        $session->set('app', $app->getUid());
        $this->addFlash('success', 'Application ' . $app->getName() . ' ajoutée à la session.');

        return $this->redirectToRoute('admin');
    }
}
