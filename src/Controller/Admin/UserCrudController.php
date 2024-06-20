<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AppRepository;

class UserCrudController extends AbstractCrudController
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher,private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/admin/user', name: 'admin_user_index')]
    public function index(AdminContext $context)
    {
        dump($context);
        return parent::index($context);
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    private function generateUUIDBase36($letter = ''): string
    {
        // Générer un ID unique de 12 caractères avec de 0 à z
        $random = Uuid::uuid4()->toString();
        $random = str_replace('-', '', $random);
        $random = base_convert($random, 16, 36);
        $random = substr($random, 0, 11);
        // add a letter to the start of the string
        $random = $letter . $random;
        return strtoupper($random);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var User $entityInstance */
        $entityInstance->setPassword(
            $this->passwordHasher->hashPassword(
                $entityInstance,
                $entityInstance->getPassword()
            )
        );

        $entityInstance->setCreatedAt(new \DateTimeImmutable());

        $entityInstance->setUid($this->generateUUIDBase36('U'));

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('firstName'),
            TextField::new('lastName'),
            // add field for relation with UserAppRelation
             AssociationField::new('userAppRelations')->hideOnForm(),
            TextField::new('password', 'Mot de passe')->onlyWhenCreating()->setHtmlAttribute('type', 'password'),
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.email != :email');
        $qb->setParameter('email', 'test@gmail.com');

        return $qb;
    }

    public function configureCrud( Crud $crud): Crud
    {
        $crud = Crud::new();
        return $crud
            ->setSearchFields(['email', 'firstName', 'lastName'])
            ->setPaginatorPageSize(30)
            ->setPaginatorRangeSize(5);
    }


}
