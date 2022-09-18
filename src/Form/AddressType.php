<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de votre adresse',
                'attr' => [
                    'placeholder' => 'Nommez de votre adresse'
                ]
            ])
            ->add('civility', ChoiceType::class, [
                'label' => 'Civilité',
                'choices' => [
                    'Mr' => 'Mr',
                    'Mme' => 'Mme',
                    'Mlle' => 'Mlle'
                ],
                'expanded' => true,
                'multiple' => false
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'N° et nom de votre rue'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal',
                'constraints' => new Length(null, 5, 5),
                'attr' => [
                    'placeholder' => 'Votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Votre ville'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => new Length(null, 10, 10),
                'attr' => [
                    'placeholder' => 'Votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
