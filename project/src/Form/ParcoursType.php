<?php

namespace App\Form;

use App\Entity\BlocUE;
use App\Entity\Parcours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anneeFormation', null, [
                'label' => 'Année de formation',
                'placeholder' => false
            ])
            ->add('rythme', null, [
                'label' => 'Rythme',
                'placeholder' => false
            ])
            ->add('specialisation', null, [
                'label' => 'Spécialisation',
                'placeholder' => false
            ])
//            ->add('blocUEs', CollectionType::class, [
//                'entry_type' => EntityType::class,
//                'entry_options' => [
//                    'class' => BlocUE::class,
//                    'choice_label' => 'label',
//                    'placeholder' => false,
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//                'prototype_name' => '__blocUE__',
//                'by_reference' => false,
//                'label' => false
//            ])
//            ->add('blocUEs', CollectionType::class, [
//                'entry_type' => ParcoursBlocUEType::class,
//                'entry_options' => [
//                    'label' => false,
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//                'prototype_name' => '__blocUE__',
//                'by_reference' => false,
//                'label' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parcours::class,
        ]);
    }
}
