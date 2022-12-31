<?php

namespace App\Form;

use App\Entity\BlocUE;
use App\Entity\Etudiant;
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
            ->add('etudiants', EntityType::class, [
                'class' => Etudiant::class,
                'label' => 'Etudiants',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parcours::class,
        ]);
    }
}
