<?php

namespace App\Form;

use App\Entity\AvanceSalaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class SalaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',  NumberType::class, array(
               
                'attr' => array(
                    'placeholder' => '  Montant'
                )))

            ->add('date_avance', DateType::class, ['widget' => 'choice',
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
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvanceSalaire::class,
        ]);
    }
}
