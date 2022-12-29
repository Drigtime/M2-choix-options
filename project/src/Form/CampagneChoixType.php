<?php

namespace App\Form;

use App\Entity\CampagneChoix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampagneChoixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', null, [
                'label' => 'Date de début',
                'widget' => 'single_text',
            ])
            ->add('dateFin', null, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
            ])
            ->add('parcours')
            ->add('blocOptions', CollectionType::class, [
                'label' => false,
                'entry_type' => BlocOptionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $campagneChoix = $event->getData();
            $parcours = $campagneChoix->getParcours();

            if ($parcours) {
                $json = [];
                foreach ($parcours->getBlocUEs() as $blocUE) {
                    $json[] = [
                        'id' => $blocUE->getId(),
                        'label' => $blocUE->getLabel(),
                    ];
                }

                $form->add('parcours', null, [
                    'label' => 'Parcours',
                    'data' => $parcours,
                    'attr' => [
                        'data-blocues' => json_encode($json)
                    ]
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CampagneChoix::class,
        ]);
    }
}
