<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\Typeconge;
use App\Entity\Rh;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
      
        ->add('datedebut', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'attr' => [
                'class' => 'form-control form-control-lg datepicker',
                'min' => (new \DateTime())->format('Y-m-d'),
            ],
            'label' => 'Date de début',
            'label_attr' => ['class' => 'form-label'],
            'format' => 'yyyy-MM-dd',
        ])
        ->add('datefin', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'attr' => [
                'class' => 'form-control form-control-lg datepicker',
                'min' => (new \DateTime())->format('Y-m-d'),
            ],
            'label' => 'Date de fin',
            'label_attr' => ['class' => 'form-label'],
            'format' => 'yyyy-MM-dd',
        ])
        ->add('nbjour', IntegerType::class, [
            'label' => 'Nombre de jours',
            'label_attr' => ['class' => 'form-label'],
            'attr' => [
                'class' => 'form-control form-control-lg',
                'min' => 1,
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'label_attr' => ['class' => 'form-label'],
            'attr' => [
                'class' => 'form-control form-control-lg',
                'rows' => 3,
            ],
        ])
        ->add('Typeconge', EntityType::class, [
            'label' => 'Type de congé',
            'label_attr' => ['class' => 'form-label'],
            'class' => Typeconge::class,
            'choice_label' => 'type',
            'attr' => [
                'class' => 'form-control form-control-lg',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}