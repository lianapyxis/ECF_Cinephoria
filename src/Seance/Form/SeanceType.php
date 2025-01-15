<?php

namespace App\Seance\Form;


use App\Entity\Seance;
use App\Entity\Film;
use App\Entity\Room;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\ChoiceList\ChoiceList;


class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('time_start', TimeType::class,[
                'label' => 'Heure de début :',
                'widget' => 'choice',
                'input'  => 'datetime'
            ])
            ->add('time_end', TimeType::class,[
                'label' => 'Heure de fin :',
                'widget' => 'choice',
                'input'  => 'datetime'
            ])
            ->add('id_room', EntityType::class, [
                'class' => Room::class,
                'mapped' => 'true',
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Salle :',
                'choice_attr'  => ChoiceList::attr($this, function (?Room $room): array {
                    $city = $room->getIdCity();
                    $cityTitle = $city->getTitle();
                    return ['data-city' => $cityTitle, 'class' => 'room-option'];
                }),
            ])
            ->add('id_film', EntityType::class, [
                'class' => Film::class,
                'mapped' => 'true',
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Film :',
            ])
            ->add('price_ttc', NumberType::class, [
                'label' => 'Tarif TTC :',
                'mapped' => 'true'
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }

}