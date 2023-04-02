<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('Nom')
            // ->add('Prenom')
            // ->add('Email')
            ->add('fileImport',FileType::class ,[
            'label'=>'Import File(XLS OR CSV file)',
            'mapped'=>false,
            'required'=>true,
            'constraints'=>[
                new File([
                    'mimeTypes'=>[
                        'text/csv',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ],
                    'mimeTypesMessage'=>'Veuillez importer un fichier csv ou xls valide'
                ])
            ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
