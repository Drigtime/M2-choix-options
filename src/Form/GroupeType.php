<?php

namespace App\Form;

use App\Entity\Main\Etudiant;
use App\Entity\Main\Groupe;
use App\Entity\Main\UE;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', IntegerType::class, [
                'label' => 'Numéro de groupe',
                'attr' => [
                    'min' => 1,
                ],
            ])
            ->add('ue', EntityType::class, [
                'label' => 'UE lié au groupe',
                'class' => UE::class,
                'choice_label' => 'label',
                'choice_attr' => function (UE $ue) {
                    return ['data-max-effectif' => $ue->getEffectif()];
                },
            ])
            ->add('etudiants', EntityType::class, [
                'label' => 'Etudiants du groupe',
                'class' => Etudiant::class,
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
}
