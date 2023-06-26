<?php

namespace App\Form;

use App\Entity\Main\Parcours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class UserImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fileImport', FileType::class, [
                'label' => 'Importer les nouveaux étudiants',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'extensions' => [
                            'csv' => ['text/csv', 'text/plain'],
                            'xls',
                            'xlsx'
                        ],
                        'extensionsMessage' => 'Le fichier doit être au format CSV, XLS ou XLSX'
                    ])
                ],
                'attr' => [
                    'accept' => '.csv, .xls, .xlsx'
                ]
            ])
            ->add('parcours', EntityType::class, [
                'class' => Parcours::class,
                'mapped' => false,
                'required' => true,
                'label' => 'Parcours',
                'placeholder' => 'Choisir un parcours'
            ]);
    }
}
