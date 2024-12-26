<?php

namespace App\Film\Form;


use App\Entity\Film;
use App\Entity\FilmGenre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('year', TextareaType::class,[
                'label' => 'Année'
            ])
            ->add('genre', EntityType::class, [
                'class' => FilmGenre::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('description', TextareaType::class)
            ->add('imgPath', FileType::class,
                [
                    'label' => 'Image',

                    // unmapped means that this field is not associated to any entity property
                    'mapped' => true,

                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => true,
                    'data_class' => null,

                    // unmapped fields can't define their validation using attributes
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '8192k',
                            'mimeTypes' => [
                                'image/avif',
                                'image/jpeg',
                                'image/webp',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid Image',
                        ])
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}