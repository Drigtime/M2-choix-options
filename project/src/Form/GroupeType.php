<?php

namespace App\Form;

use App\Entity\Main\Etudiant;
use App\Entity\Main\Groupe;
use App\Entity\Main\UE;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, [
                'label' => 'Nom du groupe',
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
                'choice_label' => (function (Etudiant $etudiant) {
                    $parcours = $etudiant->getParcours();
                    return $parcours?->getAnneeFormation()->getLabel() . ' ' . $parcours?->getlabel() . ' - ' . $etudiant->getNom() . ' ' . $etudiant->getPrenom();
                }),
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
}
