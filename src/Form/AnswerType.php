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
use Symfony\Component\Validator\Constraints as Assert;

class AnswerType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $survey = $options['survey'];
        $categories = $options['categories'];
        $need_component = $options['need_component'];
        $show_assistive = $options['show_assistive'];
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
                'label' => 'Votre commentaire (facultatif) :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez un commentaire (facultatif)',
                    'rows' => 10,
                    'cols' => 20,
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email (facultatif) :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse email (facultatif)',
                ],
            ])
            ->add('accept', CheckboxType::class, [
                'label' => 'Je confirme ma participation à ce sondage.',
                'required' => false,
            ]);

        if (!empty($choices)) {
            if ($param == "false") {
                $builder
                    ->add('propositions', ChoiceType::class, array(
                        'choices' => $choices,
                        'expanded' => true,
                        'constraints' => [
                            new Assert\NotBlank([
                                'message' => 'Attention! Vous devez choisir une proposition de réponse.',
                            ])
                        ],
                        'multiple' => false,
                    ));
            } else {
                $builder
                    ->add('propositions', EntityType::class, [
                        'class' => Proposition::class,
                        'expanded' => true,
                        'constraints' => [
                            new Assert\Count([
                                'min' => 1,
                                'minMessage' => 'Attention! Vous devez choisir au moins une proposition de réponse.',
                            ])
                        ],
                        'multiple' => true,
                        'query_builder' => function (EntityRepository $proposition) use ($survey) {
                            return $proposition->createQueryBuilder('u')
                                ->where('u.survey = :survey')
                                ->setParameter('survey', $survey);
                        },
                        'choice_label' => 'wording',
                    ]);
            }
        }
        
        if (!empty($categories)) {
            if ($show_assistive > 0) {
                $builder
                    ->add('assistives', EntityType::class,  [
                        'multiple' => true,
                        'expanded' => true,
                        'class' => Assistive::class,
                        'constraints' => [
                            new Assert\Count([
                                'min' => 1,
                                'minMessage' => 'Attention! Vous devez sélectionner au moins une aide technique.',
                            ])
                        ],
                        'choice_label' => 'name',
                        'group_by' => 'category.type',
                    ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
            'survey' => null,
            'multiple' => null,
            'need_component' => null,
            'show_assistive' => null,
            'propositions' => null,
            'categories' => null,
        ]);
    }
}
