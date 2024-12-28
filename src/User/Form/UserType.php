<?php

namespace App\User\Form;

use App\Entity\Film;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {

        $security = $this->security;

        if ($security->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('firstname', TextType::class, [
                    'label' => 'Prénom :',
                ])
                ->add('lastname', TextType::class, [
                    'label' => 'Nom :',
                ])
                ->add('username', TextType::class, [
                    'label' => 'Nom d’utilisateur :',
                ])
                ->add('email', TextType::class, [
                    'label' => 'Email :',
                ])
                ->add('password', RepeatedType::class, [
                    'label' => 'Mot de passe actuel :',
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' => ['label' => 'New Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                    'attr' => ['autocomplete' => 'off'],
                ])
                /*            ->add('film', EntityType::class, [
                                'class' => Film::class,
                                'row_attr' => [
                                    'class' => 'hidden'
                                ],
                            ])*/
                ->add('submit', SubmitType::class);

        } else {
            $builder->add('firstname', TextType::class, [
                'label' => 'Votre prénom :',
            ])
                ->add('lastname', TextType::class, [
                    'label' => 'Votre nom :',
                ])
                ->add('username', TextType::class, [
                    'label' => 'Votre nom d’utilisateur :',
                ])
                ->add('email', TextType::class, [
                    'label' => 'Votre email :',
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'Mot de passe :'
                ])
            ->add('submit', SubmitType::class);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
