<?php

namespace App\Controller\Manage;

use App\Entity\App;
use App\Entity\PageMenu;
use App\Form\PageMenuType;
use App\Utils\UidHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{appId}')]
class PageMenuController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/myapp', name: 'manage_app_index')]
    public function index(
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App $app
    ): Response
    {
        return $this->render('manage_app/index.html.twig', [
            'currentApp' => $app,
        ]);
    }

    #[Route('/pages', name: 'manage_app_pages')]
    public function pages(
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App $app
    ): Response
    {
        return $this->render('manage_app/pages.html.twig', [
            'currentApp' => $app,
            'pages' => $app->getPages(),
            'pageMenus' => $app->getPageMenus(),
        ]);
    }

    #[Route('/menu/add', name: 'manage_menu_add', condition: 'request.isXmlHttpRequest()')]
    #[Route('/menu/edit/{menuUid}', name: 'manage_menu_edit',condition: 'request.isXmlHttpRequest()')]
    public function getMenuForm(
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App $app,
        Request $request,
        #[MapEntity(mapping: ['menuUid' => 'uid'])]
        PageMenu $menu = null,

    ): Response
    {
        dump($menu);
        if ($menu === null) {
            $menu = new PageMenu();
        }

        // set action to this route
        $form = $this->createForm(PageMenuType::class, $menu, [
            'action' => $this->generateUrl('manage_menu_add', ['appId' => $app->getUid()]),
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $menu->setApp($app);
            $menu->setUid(UidHelper::generateUUIDBase36('M'));
            $this->em->persist($menu);
            $this->em->flush();

            return $this->json([
                'success' => true,
                'menu' => $menu->getId(),
            ]);
        } else if ($form->isSubmitted() && !$form->isValid()) {
            return $this->json([
                'success' => false,
                'form' => $this->renderView('manage_app/_form/_menu_form.html.twig', [
                    'form' => $form->createView(),
                ]),
            ]);
        }

        return $this->render('manage_app/_form/_menu_form.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
