<?php

namespace App\User\Form;

use App\Entity\Film;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class NewPasswordType extends AbstractType
{
    public function __construct(private readonly Security $security,private readonly EntityManagerInterface $em)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $em = $this->em;
        $security = $this->security;
        $user = $options['data'];

        $builder->add('password', PasswordType::class, [
            'label' => 'Votre nouveau mot de passe * :',
            'mapped' => false,
        ])
            ->add('email', HiddenType::class, [
            'mapped' => true,
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'data_class' => User::class,
        ]);
    }
}
