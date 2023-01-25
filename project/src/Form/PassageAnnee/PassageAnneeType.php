<?php

namespace App\Form\PassageAnnee;

use App\Form\PassageAnnee\M2_Step_1\AnneeFormationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PassageAnneeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anneeFormations', CollectionType::class, [
                'entry_type' => AnneeFormationType::class,
                'entry_options' => ['label' => false],
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => Etudiant::class,
        ]);
    }
}