<?php

namespace App\Controller\Manage;

use App\Entity\App;
use App\Entity\ContentPage;
use App\Entity\Menu;
use App\Entity\MenuItem;
use App\Entity\Page;
use App\Form\MenuType;
use App\Utils\UidHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        // if app have no menu with type main, create one
        if ($app->getMainMenu() === null) {
            $menu = new Menu();
            $menu->setApp($app);
            $menu->setName('Main Menu');
            $menu->setUid(UidHelper::generateUUIDBase36('M'));
            $menu->setType(Menu::TYPE_MAIN);
            $this->em->persist($menu);
            $this->em->flush();
        }

        // if app have no menu with type unassigned, create one
        if ($app->getUnassignedPagesMenu() === null) {
            $menu = new Menu();
            $menu->setApp($app);
            $menu->setName('Unassigned Pages');
            $menu->setUid(UidHelper::generateUUIDBase36('U'));
            $menu->setType(Menu::TYPE_UNASSIGNED);
            $this->em->persist($menu);
            $this->em->flush();
        }
        return $this->render('manage_app/pages.html.twig', [
            'currentApp' => $app,
            'mainMenu' => $app->getMainMenu(),
            'unassignedPagesMenu' => $app->getUnassignedPagesMenu(),
        ]);
    }

    #[Route('/menu/add', name: 'manage_menu_add', condition: 'request.isXmlHttpRequest()')]
    #[Route('/menu/edit/{menuUid}', name: 'manage_menu_edit', condition: 'request.isXmlHttpRequest()')]
    public function getMenuForm(
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App      $app,
        Request  $request,
        #[MapEntity(mapping: ['menuUid' => 'uid'])]
        Menu $menu = null,

    ): Response
    {
        dump($menu);
        if ($menu === null) {
            $menu = new Menu();
        }

        // set action to this route
        $form = $this->createForm(MenuType::class, $menu, [
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

    #[Route('/menu/{menuUid}/pages/types/list', name: 'menu_pages_types_list')]
    public function pageTypesList(
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App $app,
        #[MapEntity(mapping: ['menuUid' => 'uid'])]
        Menu $menu
    ): JsonResponse
    {
        return $this->json([
            'list' => $this->render('manage_app/_form/_page_types_list.html.twig', [
                'currentApp' => $app,
                'currentMenu' => $menu,
            ])->getContent(),
        ]);
    }

    #[Route('/menu/{menuUid}/pages/add', name: 'menu_pages_types_add')]
    public function pageTypesAdd(
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App $app,
        #[MapEntity(mapping: ['menuUid' => 'uid'])]
        Menu $menu,
        Request $request
    ): Response
    {
        $type = $request->get('type');
        switch ($type) {
            case 'contentPage':
                $newPage = new ContentPage();
                $newPage->setApp($app);
                $newPage->setUid(UidHelper::generateUUIDBase36('P',2));
                $newPage->setName('New Page');
                $newPage->setType(Page::TYPE_CONTENT);
                $newPage->setContent('<h1>New Page</h1> <p>Content</p> <p>Content</p> <p>Content</p>');
                $newPage->setHidden(false);
                $this->em->persist($newPage);
                $menuItem = new MenuItem();
                $menuItem->setUid(UidHelper::generateUUIDBase36('MI',2));
                $menuItem->setMenu($menu);
                $menuItem->setPage($newPage);
                $this->em->persist($menuItem);
                break;
            case 'menu':
                $newMenu = new Menu();
                $newMenu->setApp($app);
                $newMenu->setUid(UidHelper::generateUUIDBase36('M'));
                $newMenu->setName('New Menu');
                $newMenu->setType(Menu::TYPE_SUB);
                $menuItem = new MenuItem();
                $menuItem->setUid(UidHelper::generateUUIDBase36('MI',2));
                $menuItem->setMenu($menu);
                $menuItem->setSubMenu($newMenu);
                $this->em->persist($menuItem);
                $this->em->persist($newMenu);
                break;
                default:
                return $this->json([
                    'success' => false,
                    'message' => 'Invalid type',
                ]);
        }
        $this->em->flush();

        return $this->json([
            'success' => true,
            'menuItem' => $menuItem->getUid(),
        ]);
    }

    #[Route('/change-locale-lang', name: 'change_locale_lang')]
    public function changeLocaleLang(Request $request,
    #[MapEntity(mapping: ['appId' => 'uid'])]
    App $app
    ): Response
    {
        $request->getSession()->set('_locale', $request->get('lang', 'fr'));
        // redirect to the referer page
        return $this->redirect($request->headers->get('referer'));
    }
}
