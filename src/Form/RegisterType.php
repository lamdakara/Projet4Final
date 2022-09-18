<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // FORMULAIRE D'INSCRIPTION
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => new Length(null, 2, 30),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => new Length(null, 2, 30),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => new Length(null, 2, 60),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                'label' => 'Mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe.'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe.'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Crée mon compte'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
