<?php

namespace App\Room\Form;

use App\Entity\Room;
use App\Entity\Format;
use App\Entity\City;
use App\Entity\SpecialPlace;
use App\Entity\TypeSeats;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\SpecialPlace\Form\SpecialPlaceType;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'mapped' => true,
            ])
            ->add('id_city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Cinéma :',
                'mapped' => true,
            ])
            ->add('format', EntityType::class, [
                'class' => Format::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Format :',
                'mapped' => true,
            ])
            ->add('number_seats', NumberType::class, [
                'label' => 'Nombre de sièges :',
                'mapped' => true,
            ])
            ->add('number_rows', NumberType::class, [
                'label' => 'Nombre des lignes :',
                'mapped' => true,
            ])
            ->add('typeSeats', EntityType::class, [
                'class' => TypeSeats::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Format des lignes :',
                'mapped' => true,
            ])
            ->add('specialPlaces', CollectionType::class, [
                'entry_type' => SpecialPlaceType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
            'empty_data' => Room::class,
        ]);
    }

}