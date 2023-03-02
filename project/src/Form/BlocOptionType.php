<?php

namespace App\Form;

use App\Entity\BlocOption;
use App\Entity\UE;
use App\Repository\BlocUERepository;
use App\Repository\UERepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('campagneChoix')
            ->add('blocUE', null, [
                'label' => 'form.blocOption.blocUE',
                'choice_attr' => function ($bloc, $key, $value) {
                    return [
                        'data-ues' => json_encode($bloc->getBlocUeUes()->map(function ($blocUeUe) {
                            return [
                                'id' => $blocUeUe->getUE()->getId(),
                                'label' => $blocUeUe->getUE()->getLabel(),
                            ];
                        })->toArray())
                    ];
                },
                'placeholder' => false
            ])
            ->add('UEs', EntityType::class, [
                'label' => 'form.blocOption.ues',
                'class' => UE::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,
            ])
            ->add('nbUEChoix', null, [
                'label' => 'form.blocOption.nbUEChoix',
                'attr' => [
                    'min' => 1,
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $blocOption = $event->getData();
            if ($blocOption) {
                $campagneChoix = $blocOption->getCampagneChoix();
                $blocUE = $blocOption->getBlocUE();
                if ($blocUE) {
                    if ($form->has('UEs')) {
                        $form->remove('UEs');
                    }
                    $form->add('UEs', null, [
                        'label' => 'form.blocOption.ues',
                        'expanded' => true,
                        'query_builder' => function (UERepository $er) use ($blocUE) {
                            return $er->createQueryBuilder('u')
                                ->join('u.blocUEs', 'b')
                                ->where('b.id = :blocUE')
                                ->setParameter('blocUE', $blocUE->getId())
                                ->orderBy('u.label', 'ASC');
                        }
                    ]);
                }
                if ($campagneChoix) {
                    if ($form->has('blocUE')) {
                        $form->remove('blocUE');
                    }
                    $form->add('blocUE', null, [
                        'label' => 'form.blocOption.blocUE',
                        'query_builder' => function (BlocUERepository $er) use ($campagneChoix) {
                            return $er->createQueryBuilder('b')
                                ->join('b.parcours', 'p')
                                ->where('p.id = :parcours')
                                ->setParameter('parcours', $campagneChoix->getParcours()->getId())
                                ->orderBy('b.blocUECategory', 'ASC');
                        },
                        'placeholder' => false,
                        'choice_attr' => function ($bloc, $key, $value) {
                            return [
                                'data-ues' => json_encode($bloc->getBlocUeUes()->map(function ($blocUeUe) {
                                    return [
                                        'id' => $blocUeUe->getUE()->getId(),
                                        'label' => $blocUeUe->getUE()->getLabel(),
                                    ];
                                })->toArray())
                            ];
                        }
                    ]);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            if ($data) {
                $blocUEId = $data['blocUE'];
                if ($blocUEId) {
                    if ($form->has('UEs')) {
                        $form->remove('UEs');
                    }
                    $form->add('UEs', null, [
                        'label' => 'form.blocOption.ues',
                        'expanded' => true,
                        'query_builder' => function (UERepository $er) use ($blocUEId) {
                            return $er->createQueryBuilder('u')
                                ->join('u.blocUEs', 'b')
                                ->where('b.id = :blocUE')
                                ->setParameter('blocUE', $blocUEId)
                                ->orderBy('u.label', 'ASC');
                        }
                    ]);
                }
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
