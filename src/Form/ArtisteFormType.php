<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArtisteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('genre', ChoiceType::class, [
                'choices'  => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Mixte' => 'mixte',
                ],
            ])
            ->add('country', TextType::class, [
                "label" => "Nom du pays",
                "required" => true,
            ])
            ->add('nbAbonne', TextType::class, [
                "label" => "Nombre d'abonnés",
                "required" => true,
            ])
            ->add('content', TextType::class, [
                "label" => "Présentation de l'artiste",
                "required" => true,
            ])
            ->add('image', TextType::class, [
                "label" => "Photo artiste",
                "required" => true,
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,    // car ManyToMany
                'expanded' => false,    // checkboxs (false = liste déroulante multiple)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artiste::class,
        ]);
    }
}
