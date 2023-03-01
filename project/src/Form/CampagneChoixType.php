<?php

namespace App\Form;

use App\Entity\CampagneChoix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CampagneChoixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', null, [
                'label' => 'form.campagneChoix.dateDebut.label',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new Callback(function ($dateDebut, ExecutionContextInterface $context) {
                        $dateFin = $context->getRoot()->get('dateFin')->getData();
                        if ($dateFin && $dateDebut > $dateFin) {
                            $context->buildViolation('form.campagneChoix.dateDebut.errorRelatedToDateFin')
                                ->atPath('dateDebut')
                                ->setTranslationDomain('messages')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('dateFin', null, [
                'label' => 'form.campagneChoix.dateFin.label',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new Callback(function ($dateFin, ExecutionContextInterface $context) {
                        $dateDebut = $context->getRoot()->get('dateDebut')->getData();
                        if ($dateDebut && $dateFin < $dateDebut) {
                            $context->buildViolation('form.campagneChoix.dateFin.errorRelatedToDateDebut')
                                ->atPath('dateFin')
                                ->setTranslationDomain('messages')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('parcours', null, [
                'label' => 'form.campagneChoix.parcours.label',
                'choice_label' => function ($parcours) {
                    return $parcours->getAnneeFormation()->getLabel() . ' - ' . $parcours->getLabel();
                },
                'placeholder' => 'form.campagneChoix.parcours.placeholder',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CampagneChoix::class,
        ]);
    }
}
