<?php

namespace App\Form;

use App\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre du sondage :',
            ])
            ->add('question', TextareaType::class, [
                'label' => 'Consigne donnée :'
            ])
            ->add('multiple', CheckboxType::class, [
                'label' => 'Plusieurs réponses possibles.',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut du sondage :',
                'expanded' => true,
                'choices' => [
                    'Annulé' => 'Annulé',
                    'Initialisé' => 'Initialisé',
                    'Ouvert' => 'Ouvert',
                    'Fermé' => 'Fermé',
                ],
                
            ])
            ->add('closing_message', TextareaType::class, [
                'label' => 'Message de clôture du sondage :',
                'attr' => array(
                    'placeholder' => 'Le sondage est clôt.'
                ),
                'required' => false,
            ])
            ->add('propositions', CollectionType::class, array(  
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}
