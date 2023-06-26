<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Groupe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveGroupeEtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => function (Groupe $groupe) {
                    return $groupe->getLabel();
                },
                'label' => 'Groupe',
                'placeholder' => 'Choisissez un groupe',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => Etudiant::class,
        ]);
    }
}
