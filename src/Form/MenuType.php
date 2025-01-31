<?php

namespace App\Form;

use App\Entity\App;
use App\Entity\Menu;
use App\Utils\FormConstraintHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MenuType extends AbstractType
{

    public function __construct(private FormConstraintHelper $formConstraintHelper)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Menu Name',
                'attr' => $this->formConstraintHelper->getFieldAttributes(Menu::class, 'name'),
                'required' => true,
                'help' => 'Max length 8 characters',
                'help_attr' => [
                    'class' => 'text-muted'
                ],
                'help_html' => true,
                'row_attr' => [
                    'class' => 'mb-3'
                ],
            ])
            ->add('submit',SubmitType::class,[
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
