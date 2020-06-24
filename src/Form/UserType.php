<?php

namespace App\Form;

//dependency injection
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        //creation of form fields
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
                'attr' => [
                    'placeholder' => 'Entrez le prénom de l\'utilisateur',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom :',
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'utilisateur',
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Adresse email :',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse email de l\'utilisateur',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de passe :',
                    'attr' => [
                        'placeholder' => 'Entrez le mot de passe de l\'utilisateur',
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe :',
                    'attr' => [
                        'placeholder' => 'Confirmez le mot de passe de l\'utilisateur',
                    ],
                    ]
            ]);
    }

    //configuration OptionsResolver
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
