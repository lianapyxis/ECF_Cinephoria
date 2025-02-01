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

class ContactType extends AbstractType
{
    public function __construct(private readonly Security $security,private readonly EntityManagerInterface $em)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $em = $this->em;
        $security = $this->security;

            $builder->add('firstname', TextType::class, [
                'label' => 'Votre prénom* :',
                'mapped' => false,
            ])
                ->add('lastname', TextType::class, [
                    'label' => 'Votre nom* :',
                    'mapped' => false,
                ])
                ->add('object', TextType::class, [
                    'label' => 'Objet* :',
                    'mapped' => false,
                ])
                ->add('email', TextType::class, [
                    'label' => 'Votre email* :',
                    'mapped' => false,
                ])
                ->add('message', TextareaType::class, [
                    'label' => 'Votre message * :',
                    'mapped' => false,
                    'attr' => array('cols' => '5', 'rows' => '10'),
                ]);
/*                ->add('submit', SubmitType::class, [
                    'label' => 'Envoyer',
                ]);*/

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
