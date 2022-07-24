<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('caption')
            ->add('slug')
            ->add('steps')
            ->add('picture')
            ->add('nbMiams')
            ->add('duration')
            ->add('difficulty')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category')
            ->add('user')
            ->add('usersWhoFavorized')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
