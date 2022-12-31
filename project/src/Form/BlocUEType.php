<?php

namespace App\Form;

use App\Entity\BlocUE;
use App\Entity\UE;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocUEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('blocUECategory', null, [
                'label' => 'Bloc',
            ])
            ->add('ues', EntityType::class, [
                'class' => UE::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'label' => 'UEs',
            ])
//            ->add('parcours')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocUE::class,
        ]);
    }
}
