<?php

namespace App\Form;

use App\Entity\BlocUECategory;
use App\Entity\UE;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, [
                'label' => 'Nom de l\'UE',
            ])
            ->add('blocUECategories', EntityType::class, [
                'class' => BlocUECategory::class,
                'label' => 'Categorie de bloc UE',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('active', null, [
                'label' => 'Actif',
                'required' => false,
            ])//            ->add('blocOptions')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UE::class,
        ]);
    }
}
