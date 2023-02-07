<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Parcours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveEtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('parcours', EntityType::class, [
                'class' => Parcours::class,
                'choice_label' => 'label',
                'label' => 'Parcours',
                'placeholder' => 'Choisissez un parcours',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
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
