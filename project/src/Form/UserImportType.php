<?php

namespace App\Form;

use App\Entity\Main\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('Nom')
            // ->add('Prenom')
            // ->add('Email')
            ->add('fileImport', FileType::class, [
                'label' => 'Importer les nouveaux étudiants',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'text/csv'
                        ],
                        'mimeTypesMessage' => 'Veuillez importer un fichier csv valide'
                    ])
                ],
                'attr' => [
                    'accept' => '.csv'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
