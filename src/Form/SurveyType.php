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
                'attr' => [
                    'autofocus' => true,
                    'placeholder' => 'Entrez le titre du sondage',
                ],
            ])
            ->add('question', TextareaType::class, [
                'label' => 'Question :',
                'attr' => [
                    'placeholder' => 'Entrez la question',
                    'rows' => 10,
                    'cols' => 20,
                ],
            ])
            ->add('information', TextareaType::class, [
                'label' => 'Informations complémentaires :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez des informations complémentaires',
                    'rows' => 10,
                    'cols' => 20,
                ],
            ])
            ->add('show_assistive', CheckboxType::class, [
                'label' => 'Afficher la liste des technologies d\'assistance',
                'required' => false,
            ])
            ->add('need_component', CheckboxType::class, [
                'label' => 'Besoin d\'implémenter un composant technique',
                'required' => false,
            ])
            ->add('multiple', CheckboxType::class, [
                'label' => 'Plusieurs réponses possibles.',
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut du sondage :',
                'expanded' => true,
                'data' => $options['status'],
                'choices' => [
                    'Brouillon' => 'Brouillon',
                    'Ouvert' => 'Ouvert',
                    'Fermé' => 'Fermé',
                ],
            ])
            ->add('closing_message', TextareaType::class, [
                'label' => 'Message de clôture du sondage :',
                'data' => 'Le sondage a été clôturé.',
                'attr' => [
                    'placeholder' => 'Entrez le message de clôture du sondage',
                    'rows' => 10,
                    'cols' => 20,
                ],
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
            'status' => null,
        ]);
    }
}
