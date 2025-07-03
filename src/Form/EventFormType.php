<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Media;
use App\Entity\Musique;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            // ->add('date')
            ->add('url', TextType::class, [
                "label" => "Lien des billets",
                "required" => true,
            ])
            ->add('prog', TextType::class, [
                "label" => "Nom de ou des artistes",
                "required" => true,
            ])
            ->add('content', TextType::class, [
                "label" => "Résumer du festival",
                "required" => true,
            ])
            ->add('price', NumberType::class, [
                "label" => "Prix du festival",
                "required" => true,
            ])
            ->add('musique', EntityType::class, [
                'class' => Musique::class,
                'choice_label' => 'id',
            ])
            ->add('media', EntityType::class, [
                'class' => Media::class,
                'choice_label' => 'id',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'id',
                'multiple' => true,
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
