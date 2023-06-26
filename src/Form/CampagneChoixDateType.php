<?php

namespace App\Form;

use App\Entity\Main\BlocUE;
use App\Entity\Main\CampagneChoix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampagneChoixDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', null, [
                'label' => 'form.campagneChoix.dateDebut.label',
                'widget' => 'single_text',
            ])
            ->add('dateFin', null, [
                'label' => 'form.campagneChoix.dateFin.label',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CampagneChoix::class,
        ]);
    }
}
