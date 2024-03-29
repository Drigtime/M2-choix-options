<?php

namespace App\Form\PassageAnnee\Step_1;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnneeFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etudiants', CollectionType::class, [
                'entry_type' => EtudiantType::class,
                'entry_options' => ['label' => false],
            ]);
    }
}
