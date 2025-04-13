<?php

namespace App\Form;

use App\Entity\ModificationInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\TypeModification;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ModificationInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('TypeModification', EntityType::class, [
            'expanded' => false,
            'class'=> TypeModification::class,
            'choice_label'  => 'type',
            'multiple' => false,
            ])
            ->add('libelle',  TextType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => '  Description'
                )
           ))
           
        ;
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModificationInformation::class,
        ]);
    }
}
