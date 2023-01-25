<?php

namespace App\Form\PassageAnnee\M2_Step_1;

use App\Entity\EtudiantUE;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EtudiantUEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('acquis', null, [
                'label' => 'Acquis',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EtudiantUE::class,
        ]);
    }
}