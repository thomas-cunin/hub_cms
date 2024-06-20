<?php

namespace App\Controller\Admin;

use App\Entity\App;
use App\Repository\AppRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;

class AppCrudController extends AbstractCrudController
{
    public function __construct()
    {
    }

    public static function getEntityFqcn(): string
    {
        return App::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->setDisabled(true),
            TextField::new('uid')->setDisabled(true),
            TextField::new('name')->setRequired(true)->setHelp('The name of the app'),
            DateTimeField::new('createdAt')->setDisabled(true),
            DateTimeField::new('updatedAt')->hideWhenCreating()->setDisabled(true),
            AssociationField::new('userAppRelations')->setHelp('The users of the app')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Crud::PAGE_DETAIL);
    }
}
