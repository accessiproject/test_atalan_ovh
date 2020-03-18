<?php

namespace App\Form;

use App\Entity\TechnicalComponent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TechnicalComponentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('choice', ChoiceType::class, [
                'label' => 'L\'implémentation',
                'expanded' => true,
                'data' => true,
                'choices'  => [
                    'Code source' => true,
                    'URL' => false,
                ],
            ])
            ->add('code', TextareaType::class, [
                'label' => 'Code source',
            ])
            ->add('url', UrlType::class, [
                'label' => 'Adresse URL',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TechnicalComponent::class,
        ]);
    }
}
