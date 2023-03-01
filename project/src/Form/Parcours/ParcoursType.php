<?php

namespace App\Form\Parcours;

use App\Entity\Parcours;
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
                'label' => 'form.parcours.anneeFormation',
                'placeholder' => false
            ])
            ->add('label', null, [
                'label' => 'form.parcours.label',
            ])
            ->add('blocUEs', CollectionType::class, [
                'entry_type' => BlocUEType::class,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
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
