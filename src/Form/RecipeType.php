<?php

namespace App\Form;

use App\Entity\Step;
use App\Entity\User;
use App\Entity\Recipe;
use App\Form\UserType;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de la Recette"])
            ->add('caption', TextType::class, [
                'label' => 'Brève histoire de votre Recette',
                'required' => false
                ])

            ->add(
                $builder->create('steps', FormType::class, 
                [
                    'required' => false
                ])
                    ->add('etape1', TextareaType::class)
                    ->add('etape2', TextareaType::class)
                    ->add('etape3', TextareaType::class) 
                    ->add('etape4', TextareaType::class) 
                    ->add('etape5', TextareaType::class)
                    ->add('etape6', TextareaType::class)
                    ->add('etape7', TextareaType::class)
                    ->add('etape8', TextareaType::class)
                    ->add('etape9', TextAreaType::class)
            )

          
            ->add('image', FileType::class ,[
                'mapped' => false,
                'required' => false
                ])
            // We need the option if we want to delet or no the picture
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $e){
                $form = $e->getForm();
                $recipe = $e->getData();

                if($recipe->getId()){
                    $form->add('deleteImage', ChoiceType::class, [
                        'mapped' => false,
                        'label' => "Suppression de l'image de la recette (sera remplacer par l'image par défaut)",
                        'choices' => [
                            "oui" => true,
                            "non" => false
                        ],
                        'expanded' => true,
                        'data' => false
                    ]);
                }
            })
            

            ->add('duration')
            ->add('difficulty')
            //->add('createdAt')
            //->add('updatedAt')
            ->add('category', EntityType::class, [
                'label' => "Choisissez la catégorie de la Recette",
                'choice_label' => 'name',
                'class' => Category::class,])
            ->add('user', EntityType::class, [
                'class' => User::class
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
