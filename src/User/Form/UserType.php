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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UserType extends AbstractType
{
    public function __construct(private readonly Security $security,private readonly EntityManagerInterface $em)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $em = $this->em;
        $security = $this->security;
        $user = $options['data'];

        $isCreated = $em->contains($user);
/*        $originalPassword = '';
        if($isCreated) {
            $originalUser = $em->find('App\Entity\User',$user->getId());
            $originalPassword = $originalUser->getPassword();
        }*/

        if ($security->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('id', HiddenType::class, [
                    'mapped' => true
                ])
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
/*                ->add('password', RepeatedType::class, [
                    'label' => 'Mot de passe actuel :',
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les champs du nouveau mot de passe doivent correspondre.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => $isCreated ? false : true,
                    'first_options' => ['label' => 'Nouveau mot de passe :'],
                    'second_options' => ['label' => 'Répétez le nouveau mot de passe :'],
                    'attr' => ['autocomplete' => 'off'],
                    'empty_data' => $originalPassword
                ])*/
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
    public function getOriginalPassword($id = null){


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
