<?php

namespace App\Form\PassageAnnee\M2_Step_1;

use App\Entity\AnneeFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AnneeFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parcours', CollectionType::class, [
                'entry_type' => ParcoursType::class,
                'entry_options' => ['label' => false],
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnneeFormation::class,
        ]);
    }
}