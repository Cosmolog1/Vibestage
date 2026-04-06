<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Artiste;
use App\Entity\Category;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom de l'evenement",
                "required" => true,
            ])
            ->add('dateStart', DateType::class, [
                'widget' => 'single_text',
                "required" => true,
            ])

            ->add('dateEnd', DateType::class, [
                'widget' => 'single_text',
                "required" => true,
            ])

            ->add('url', TextType::class, [
                "label" => "Lien des billets",
                "required" => false,
            ])
            ->add('artistes', EntityType::class, [
                'label' => 'Artistes',
                'class' => Artiste::class,
                'choice_label' => 'name',
                'multiple' => true,

            ])
            ->add('content', TextType::class, [
                "label" => "Résumer du festival",
                "required" => true,
            ])

            ->add('location', EntityType::class, [
                'class' => Location::class,
                "label" => "Nom de la ville",
                'choice_label' => 'city',
                "required" => true,
                'multiple' => true,
            ])

            ->add('image', TextType::class, [
                "label" => "Photo event",
                "required" => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
