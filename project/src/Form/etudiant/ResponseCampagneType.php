<?php

namespace App\Form\Etudiant;

use App\Entity\CampagneChoix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Choix;
use App\Form\Etudiant\BlocOptionType;


class ResponseCampagneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        dump(2);
        $builder->add('blocOptions', CollectionType::class, [
            'entry_type' => BlocOptionType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ])->addEventListener(FormEvents::PRE_SET_DATA, function () {
            dump(5);
        });
        dump(3);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        dump(4);
        $resolver->setDefaults([
            'data_class' => CampagneChoix::class,
        ]);
    }
}
