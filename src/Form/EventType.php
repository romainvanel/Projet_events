<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'évènement",
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "le nom de l'évènement est obligatoire"
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => "Le nom de l'évènement ne doit pas dépasser 255 caractères"
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de l'évènement",
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "La desciption de l'évènement est obligatoire"
                    ])
                ]
            ])
            ->add('startDate', DateType::class, [
                'label' => "Début de l'évènement",
                'required' => false,
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir la date de début de l'évènement"
                    ])
                ]
            ])
            ->add('endDate', DateType::class, [
                'label' => "Fin de l'évènement",
                'required' => false,
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir la date de fin de l'évènement"
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Editer",
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
