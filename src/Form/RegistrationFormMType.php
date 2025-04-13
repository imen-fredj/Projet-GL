<?php

namespace App\Form;

use App\Entity\Rh;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      
        ->add('nom',  TextType::class, array(
               
            'attr' => array(
                'placeholder' => '  Nom'
            )
       ))
        ->add('prenom',  TextType::class, array(
           
            'attr' => array(
                'placeholder' => '  Prenom'
            )
       ))
            ->add('cin',  IntegerType::class, array(
                'constraints' => 
                    new Length([
                        'min' => 8,
                        'minMessage' => "Carte d'identité doit avoir 8 chiffre",
                        'max' => 4096,
                    ]),
                'attr' => array(
                    'placeholder' => '    CIN'
                )))
                ->add('adresse')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rh::class,
        ]);
    }
}
