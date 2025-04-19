<?php

namespace App\Form;

use App\Entity\Conge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use App\Entity\Typeconge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                // 'constraints' => [
                //     new NotBlank(),
                //     new LessThanOrEqual([
                //         'propertyPath' => 'parent.all[datefin].data',
                //         'message' => 'La date de début doit être avant la date de fin'
                //     ]),
                //     new GreaterThanOrEqual([
                //         'value' => 'today',
                //         'message' => 'La date de début doit être supérieure ou égale à aujourd\'hui'
                //     ])
                // ]
            ])
            ->add('datefin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                // 'constraints' => [
                //     new NotBlank(),
                //     new GreaterThanOrEqual([
                //         'propertyPath' => 'parent.all[datedebut].data',
                //         'message' => 'La date de fin doit être après la date de début'
                //     ])
                // ]
            ])
            ->add('nbjour', IntegerType::class, [
                'label' => 'Nombre de jours',
                'label_attr' => ['class' => 'form-label'],
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'min' => 1,
                ],
                'disabled' => true // Le champ est calculé automatiquement
            ])
            ->add('Typeconge', EntityType::class, [ // Add this field
                'class' => Typeconge::class,
                'choice_label' => 'type',
                'label' => 'Type de congé'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}