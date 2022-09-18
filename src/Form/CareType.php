<?php

namespace App\Form;

use App\Entity\Care;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description...'
                ]
            ])
            ->add('duree', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Durée de service ( en minutes )'
                ]
            ])
            ->add('prix', MoneyType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Care::class,
        ]);
    }
}
