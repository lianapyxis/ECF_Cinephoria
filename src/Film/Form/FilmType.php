<?php

namespace App\Film\Form;


use App\Entity\Film;
use App\Entity\FilmGenre;
use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Asset\Packages;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if (null !== $options['data']->getImgPath()) {
            $isEmpty = false;
        } else {
            $isEmpty = true;
        }

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
            ])
            ->add('year', TextType::class,[
                'label' => 'Année :'
            ])
            ->add('genres', EntityType::class, [
                'class' => FilmGenre::class,
                'mapped' => 'true',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'label' => ' ',
                'by_reference' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'attr' => array('cols' => '5', 'rows' => '10'),
            ])
            ->add('imgPath', FileType::class,
                [
                    'label' => ' ',

                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,

                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => $isEmpty,
                    'data_class' => null,

                    // unmapped fields can't define their validation using attributes
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '528192k',
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
            ->add('rating', EntityType::class, [
                'class' => Rating::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Rating :',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
            'empty_data' => Film::class,
        ]);
    }

}