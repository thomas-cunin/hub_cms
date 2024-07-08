<?php

namespace App\Controller\Manage;

use App\Entity\App;
use App\Entity\ContentPage;
use App\Form\ContentPageEditorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{appId}')]
class ContentPageController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/page/{pageUid}/edit', name: 'edit_page')]
    public function index(
        Request $request,
        #[MapEntity(mapping: ['appId' => 'uid'])]
        App $app,
        #[MapEntity(mapping: ['pageUid' => 'uid'])]
        ContentPage $page
    ): Response
    {

        $form = $this->createForm(ContentPageEditorType::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('edit_page', ['appId' => $app->getUid(), 'pageUid' => $page->getUid()]);
        }
        return $this->render('edit_page/content_page/edit.html.twig', [
            'currentApp' => $app,
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }
}
