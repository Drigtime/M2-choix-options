<?php

namespace App\Form;

use App\Entity\BlocOption;
use App\Repository\BlocUERepository;
use App\Repository\UERepository;
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
                'label' => 'Bloc UE',
                'attr' => [
                    'data-ues' => json_encode([])
                ],
                'placeholder' => false
            ])
            ->add('UE', null, [
                'label' => 'UE',
                'expanded' => true,
            ])
            ->add('nbUEChoix', null, [
                'label' => 'Nombre d\'UE à choisir',
                'attr' => [
                    'min' => 1,
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $blocOption = $event->getData();
            $campagneChoix = $blocOption->getCampagneChoix();
            if ($blocOption) {
                $blocUE = $blocOption->getBlocUE();
                if ($blocUE) {
                    $form->add('UE', null, [
                        'label' => 'UE',
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
            }
            if ($campagneChoix) {
                // based on $campagneChoix->getParcours bloc ue
                $form->add('blocUE', null, [
                    'label' => 'Bloc UE',
                    'query_builder' => function (BlocUERepository $er) use ($campagneChoix) {
                        return $er->createQueryBuilder('b')
                            ->join('b.parcours', 'p')
                            ->where('p.id = :parcours')
                            ->setParameter('parcours', $campagneChoix->getParcours()->getId())
                            ->orderBy('b.blocUECategory', 'ASC');
                    },
                    'placeholder' => false,
                    'choice_attr' => function ($choice, $key, $value) {
                        return [
                            'data-ues' => json_encode($choice->getUEs()->map(function ($ue) {
                                return [
                                    'id' => $ue->getId(),
                                    'label' => $ue->getLabel(),
                                ];
                            })->toArray())
                        ];
                    }
                ]);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            if ($data) {
                $blocUEId = $data['blocUE'];
                if ($blocUEId) {
                    $form->add('UE', null, [
                        'label' => 'UE',
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
