<?php

namespace App\Form\Parcours;

use App\Entity\Main\BlocUE;
use App\Entity\Main\BlocUeUe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlocUEType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', null, [
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
            ->add('nbUEsOptional', null, [
                'label' => 'form.blocUE.nbUEsOptional.label',
                'attr' => [
                    'min' => 0,
                ],
                'constraints' => [
                    new Callback(function ($nbUEsOptional, ExecutionContextInterface $context) {
                        $blocUeUes = $context->getObject()->getParent()->getData()->getBlocUeUes();
                        if ($blocUeUes->count() > 0) {
                            $blocUeUesOptional = $blocUeUes->filter(function (BlocUeUe $blocUeUe) {
                                return $blocUeUe->isOptional();
                            });
                            if ($nbUEsOptional >= $blocUeUesOptional->count() && $blocUeUesOptional->count() > 0) {
                                $context->buildViolation($this->translator->trans('form.blocUE.nbUEsOptional.error.max'))
                                    ->addViolation();
                            } elseif ($nbUEsOptional == 0 && $blocUeUesOptional->count() > 0) {
                                $context->buildViolation($this->translator->trans('form.blocUE.nbUEsOptional.error.min'))
                                    ->addViolation();
                            }
                        }
                    })
                ]
            ])
            ->add('blocUeUes', CollectionType::class, [
                'label' => false,
                'entry_type' => BlocUeUeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__ues__',
                'mapped' => true,
            ]);

        // add event listener to check if all blocUeUes->ue are unique

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlocUE::class,
        ]);
    }
}
