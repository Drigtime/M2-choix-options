<?php

namespace App\Form\Etudiant;

use App\Entity\CampagneChoix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ResponseCampagneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('blocOptions', CollectionType::class, [
//                'entry_type' => BlocOptionType::class,
//                'entry_options' => ['label' => false],
//                'allow_add' => false,
//                'allow_delete' => false,
//                'label' => false,
//            ])
            ->add('parcours', ParcoursType::class, [
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
             'data_class' => CampagneChoix::class,
        ]);
    }
}
