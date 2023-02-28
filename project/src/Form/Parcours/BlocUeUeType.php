<?php

namespace App\Form\Parcours;

use App\Entity\BlocUECategory;
use App\Entity\BlocUeUe;
use App\Entity\UE;
use App\Entity\UeBlocUeCategory;
use App\Form\Etudiant\ChoixType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ])
            ->add('statut', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Obligatoire' => '1',
                    'Optionnel' => '2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocUeUe::class,
        ]);
    }
}
