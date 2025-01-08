<?php

namespace App\Room\Form;

use App\Entity\Room;
use App\Entity\Film;
use App\Entity\Format;
use App\Entity\City;
use App\Entity\TypeSeats;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
            ])
            ->add('id_city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Cinéma :',
            ])
            ->add('format', EntityType::class, [
                'label' => 'Format :',
                'class' => Format::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('number_seats', NumberType::class, [
                'label' => 'Nombre de sièges :',
            ])
            ->add('number_rows', NumberType::class, [
                'label' => 'Nombre des lignes :',
            ])
            ->add('typeSeats', EntityType::class, [
                'class' => TypeSeats::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Format des lignes :',
                'by_reference' => false,
            ])
            ->add('specialPlaces', HiddenType::class, [
                'label' => ' ',
                'mapped' => false,
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