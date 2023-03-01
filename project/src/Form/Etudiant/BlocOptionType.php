<?php

namespace App\Form\Etudiant;

use App\Entity\BlocOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class BlocOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('choixes', CollectionType::class, [
            'entry_type' => ChoixType::class,
            'entry_options' => ['label' => false],
            'allow_add' => false,
            'allow_delete' => false,
            'label' => false,
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $blocOption = $event->getData();
            $form = $event->getForm();
            
            if ($blocOption && $form->has('choixes')) {
                $choixes = $blocOption->getChoixes()->toArray();
                usort($choixes, function ($a, $b) {
                    return $a->getOrdre() <=> $b->getOrdre();
                });

                $form->remove('choixes');
                $form->add('choixes', CollectionType::class, [
                    'entry_type' => ChoixType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => false,
                    'allow_delete' => false,
                    'label' => false,
                    'data' => $choixes,
                ]);
            }

        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocOption::class,
        ]);
    }
}