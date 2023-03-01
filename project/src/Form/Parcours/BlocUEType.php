<?php

namespace App\Form\Parcours;

use App\Entity\BlocUE;
use App\Entity\UE;
use App\Repository\UERepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocUEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('blocUECategory', null, [
                'label' => 'form.blocUE.blocUECategory',
                'choice_attr' => function ($choice, $key, $value) {
                    return [
                        'data-ues' => json_encode($choice->getUEs()->map(function ($ue) {
                            return [
                                'id' => $ue->getId(),
                                'label' => $ue->getLabel(),
                            ];
                        })->toArray())
                    ];
                }
            ])
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
            ->add('blocOption', BlocOptionType::class, [
                'label' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocUE::class,
        ]);
    }
}
