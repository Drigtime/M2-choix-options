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

class CampagneChoixType extends AbstractType
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
            ])
            ->add('parcours', null, [
                'label' => 'form.campagneChoix.parcours.label',
                'choice_label' => function ($parcours) {
                    return $parcours->getAnneeFormation()->getLabel() . ' - ' . $parcours->getLabel();
                },
                'choice_attr' => function ($parcours) {
                    return [
                        'data-blocs-ue' => json_encode([
                            'label' => $parcours->getLabel(),
                            'blocUEs' => $parcours->getBlocUEs()->map(function (BlocUE $bloc) {
                                return [
                                    'id' => $bloc->getId(),
                                    'label' => $bloc->__toString(),
                                    'categorie_id' => $bloc->getCategory()->getId(),
                                    'ues' => $bloc->getBlocUeUes()
                                        ->filter(function ($blocUeUe) {
                                            return $blocUeUe->isOptional();
                                        })
                                        ->map(function ($blocUeUe) {
                                            $ue = $blocUeUe->getUE();
                                            return [
                                                'id' => $ue->getId(),
                                                'label' => $ue->getLabel(),
                                                'effectif' => $ue->getEffectif(),
                                                'nbrGroupe' => $ue->getNbrGroupe(),
                                            ];
                                        })
                                        ->getValues(),
                                ];
                            })->toArray()
                        ])
                    ];
                },
                'multiple' => true,
                'placeholder' => false,
            ])
            ->add('blocOptions', CollectionType::class, [
                'label' => false,
                'entry_type' => BlocOptionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $campagneChoix = $event->getData();
            $parcours = $campagneChoix->getParcours();

            if ($parcours) {
                if ($form->has('parcours')) {
                    $form->remove('parcours');
                }
                $form->add('parcours', null, [
                    'label' => 'form.campagneChoix.parcours.label',
                    'choice_label' => function ($parcours) {
                        return $parcours->getAnneeFormation()->getLabel() . ' - ' . $parcours->getLabel();
                    },
                    'choice_attr' => function ($parcours) {
                        return [
                            'data-blocs-ue' => json_encode([
                                'id' => $parcours->getId(),
                                'label' => $parcours->getLabel(),
                                'blocUEs' => $parcours->getBlocUEs()->map(function (BlocUE $bloc) {
                                    return [
                                        'id' => $bloc->getId(),
                                        'label' => $bloc->__toString(),
                                        'categorie_id' => $bloc->getCategory()->getId(),
                                        'ues' => $bloc->getBlocUeUes()
                                            ->filter(function ($blocUeUe) {
                                                return $blocUeUe->isOptional();
                                            })
                                            ->map(function ($blocUeUe) {
                                                $ue = $blocUeUe->getUE();
                                                return [
                                                    'id' => $ue->getId(),
                                                    'label' => $ue->getLabel(),
                                                    'effectif' => $ue->getEffectif(),
                                                    'nbrGroupe' => $ue->getNbrGroupe(),
                                                ];
                                            })
                                            ->getValues(),
                                    ];
                                })->toArray()
                            ])
                        ];
                    },
                    'placeholder' => false,
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
