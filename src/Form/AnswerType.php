<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Assistive;
use App\Entity\Category;
use App\Entity\Proposition;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\PropositionRepository;
use App\Repository\AssistiveRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnswerType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $survey = $options['survey'];
        $multiple = $options['multiple'];

        if ($multiple == 1)
            $param = "true";
        else
            $param = "false";

        $choices = array();
        foreach ($options['propositions'] as $proposition) {
            $id = $proposition->getId();
            $wording = $proposition->getWording();
            $choices[$wording] = $id;
        }
        
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire (facultatif) :',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email (facultatif) :',
                'required' => false,
            ])
            ->add('accept', CheckboxType::class, [
                'label' => 'J\'accepte de partager mes données techniques à la Société Atalan.',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer ma réponse',
            ]);
        
        if ($param == "false") {    
            $builder
                ->add('propositions', ChoiceType::class, array(
                    'choices' => $choices,
                    'expanded' => true,
                    'multiple' => false
                ));
        } else {
            $builder
                ->add('propositions', EntityType::class, [
                    'class' => Proposition::class,
                    'expanded' => true,
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $proposition) use ($survey) {
                        return $proposition->createQueryBuilder('u')
                            ->where('u.survey = :survey')
                            ->setParameter('survey', $survey);
                    },
                    'choice_label' => 'wording',
                ]);
        }
        
        $builder
            ->add('assistives', EntityType::class,  [
                'multiple' => true,
                'expanded' => true,
                'class' => Assistive::class,
                'choice_label' => 'name',
                'query_builder' => function(AssistiveRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.name','ASC');
                },
                'group_by' => 'category.type',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
            'survey' => null,
            'multiple' => null,
            'propositions' => null,
            'categories' => null,
        ]);
    }

    
}
