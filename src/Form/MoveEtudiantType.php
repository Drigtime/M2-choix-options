<?php

namespace App\Form;

use App\Entity\Main\Parcours;
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
                'choice_label' => function (Parcours $parcours) {
                    return $parcours->getAnneeFormation()->getLabel() . ' - ' . $parcours->getLabel();
                },
                'label' => 'Parcours',
                'placeholder' => 'Choisissez un parcours',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
