<?php

namespace App\Form\Parcours;

use App\Entity\BlocOption;
use App\Entity\UE;
use App\Repository\BlocUERepository;
use App\Repository\UERepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ues', CollectionType::class, [
                'label' => false,
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => UE::class,
                    'choice_label' => 'label',
                    'choice_value' => 'id',
                    'query_builder' => function (UERepository $ueRepository) {
                        return $ueRepository->createQueryBuilder('ue')
                            ->orderBy('ue.label', 'ASC');
                    },
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__ues__',
                'mapped' => true,
            ])
            ->add('nbUEChoix', NumberType::class, [
                'label' => 'form.blocOption.nbUEChoix',
                'attr' => [
                    'min' => 0,
                ],
                // integer
                'html5' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocOption::class,
        ]);
    }
}
