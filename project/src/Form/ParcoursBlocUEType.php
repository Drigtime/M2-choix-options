<?php

namespace App\Form;

use App\Entity\Main\BlocUE;
use App\Entity\Main\UE;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParcoursBlocUEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('blocUE', EntityType::class, [
                'class' => BlocUE::class,
                'choice_label' => 'label',
                'placeholder' => false,
            ])
            ->add('ues', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => UE::class,
                    'choice_label' => 'label',
                    'placeholder' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__ue__',
                'by_reference' => false,
                'label' => false
            ])
//            ->add('parcours')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => BlocUE::class,
        ]);
    }
}
