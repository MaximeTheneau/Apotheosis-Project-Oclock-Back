<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email du compte' 
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Manager' => 'ROLE_MANAGER',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                $form = $event->getForm();
                $user = $event->getData();

                if(!$user->getId()){
                    $form->add('password', PasswordType::class, [
                        'label' => 'Mot de Passe de l\'utilisateur'
                    ]);
                } else {
                    $form->add('password', PasswordType::class, [
                        'required' => false,
                        'mapped' => false,
                        'label' => 'Mot de Passe de l\'utilisateur'
                    ] );
                }
            })
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo de l\'uitlisateur'
            ])
            ->add('avatar', FileType::class , [
                'mapped' => false,
                'required' => false
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $e){
                $form = $e->getForm();
                $user = $e->getData();

                if($user->getId()){
                    $form->add('deleteImage', ChoiceType::class, [
                        'mapped' => false,
                        'label' => "Suppression de l'avatar de l'utilisateur (sera remplacer par l'image par défaut)",
                        'choices' => [
                            "oui" => true,
                            "non" => false
                        ],
                        'expanded' => true,
                        'data' => false
                    ]);
                }
            })
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
