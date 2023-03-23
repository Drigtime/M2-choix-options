<?php

namespace App\Form\Parcours;

use App\Entity\Main\BlocUE;
use App\Entity\Main\BlocUEUe;
use App\Entity\Main\UE;
use App\Repository\UERepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocUeUeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ue', EntityType::class, [
                'label' => false,
                'class' => UE::class,
                'choice_label' => 'label',
                'choice_value' => 'id',
                'query_builder' => function (UERepository $ueRepository) {
                    return $ueRepository->createQueryBuilder('ue')
                        ->orderBy('ue.label', 'ASC');
                },
            ])
            ->add('optional', HiddenType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocUeUe::class,
        ]);
    }
}
