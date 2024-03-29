<?php

namespace App\Form;

use App\Entity\Main\BlocUE;
use App\Entity\Main\UE;
use App\Repository\UERepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocUEType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('blocUECategory', null, [
                'label' => 'form.blocUE.category',
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
            ])
            ->add('ues', EntityType::class, [
                'label' => 'form.blocUE.ues',
                'class' => UE::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,
            ]);

        // event
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $blocUE = $event->getData();
            if ($blocUE) {
                $blocUECategory = $blocUE->getBlocUECategory();
                if ($blocUECategory) {
                    if ($form->has('ues')) {
                        $form->remove('ues');
                    }
                    $form->add('ues', EntityType::class, [
                        'label' => 'form.blocUE.ues',
                        'class' => UE::class,
                        'choice_label' => 'label',
                        'multiple' => true,
                        'expanded' => true,
                        'mapped' => true,
                        'query_builder' => function (UERepository $er) use ($blocUECategory) {
                            return $er->createQueryBuilder('u')
                                // ues has many blocUECategory so it is blocUECategories
                                ->join('u.blocUECategories', 'b')
                                ->where('b.id = :blocUECategory')
                                ->setParameter('blocUECategory', $blocUECategory->getId());
                        }
                    ]);
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $blocUECategoryId = $event->getData()['blocUECategory'];
            if ($blocUECategoryId) {
                if ($form->has('ues')) {
                    $form->remove('ues');
                }
                $form->add('ues', EntityType::class, [
                    'label' => 'form.blocUE.ues',
                    'class' => UE::class,
                    'choice_label' => 'label',
                    'multiple' => true,
                    'expanded' => true,
                    'mapped' => true,
                    'query_builder' => function (UERepository $er) use ($blocUECategoryId) {
                        return $er->createQueryBuilder('u')
                            ->join('u.blocUECategories', 'b')
                            ->where('b.id = :blocUECategory')
                            ->setParameter('blocUECategory', $blocUECategoryId)
                            ->orderBy('u.label', 'ASC');
                    }
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocUE::class,
        ]);
    }
}
