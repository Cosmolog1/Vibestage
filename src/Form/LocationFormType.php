<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LocationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', TextType::class, [
                "label" => "Nom du pays",
                "required" => true,
            ])
            ->add('region', TextType::class, [
                "label" => "Nom de la region",
                "required" => true,
            ])
            ->add('departement', TextType::class, [
                "label" => "Nom du departement",
                "required" => true,
            ])
            ->add('city', TextType::class, [
                "label" => "Nom de la ville",
                "required" => true,
            ])
            ->add('events', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
