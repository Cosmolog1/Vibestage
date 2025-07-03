<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Artiste;
use App\Entity\Musique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArtisteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom de l'artiste",
                "required" => true,
            ])
            ->add('genre', TextType::class, [
                "label" => "Genre de la musique",
                "required" => true,
            ])
            ->add('country', TextType::class, [
                "label" => "Nom du pays",
                "required" => true,
            ])
            ->add('nbAbonne', NumberType::class, [
                "label" => "Nombre d'abonnées",
                "required" => true,
            ])
            ->add('content', TextareaType::class, [
                "label" => "Résumer de l'artiste",
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,
        ]);
    }
}
