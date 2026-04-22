<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('createdAt')
            // ->add('updateAt')
            ->add('content', TextareaType::class, [
                "label" => "Contenu du commentaire",
                "required" => true,
                "attr" => [
                    "rows" => 4, // hauteur par défaut
                    "placeholder" => "Écrire un commentaire..."
                ]
            ]);
        // ->add('user', EntityType::class, [
        //     'class' => User::class,
        //     'choice_label' => 'id',
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
