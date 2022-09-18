<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Care;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('debut', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'label' => 'Date de début'
            ])
            ->add('fin', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
               
                'label' => 'Date de fin'
            ])
            ->add('service', EntityType::class, [
                'class' => Care::class,
                'choice_label' => 'titre',
                'choice_label' => function ($massage) {
                    return $massage->getTitre() .' ( '. $massage->getPrix() . ' € )';
                },
                'label' => 'Préstation de service',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
