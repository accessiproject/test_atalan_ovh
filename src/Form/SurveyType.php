<?php

namespace App\Form;

use App\Entity\Survey;
use App\Form\PropositionType;
use App\Form\TechnicalComponentType;
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
            ->add('title', TextType::class, [
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
             ]);
            $builder->add('propositions', CollectionType::class, [
                'entry_type' => PropositionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
            $builder->add('technicalComponents', CollectionType::class, [
                'entry_type' => TechnicalComponentType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}
