<?php

namespace App\Form;

use App\Entity\Rh;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, array(   
            'label'=>false, 
            
            'attr' => array(
                'placeholder' => '  Email',
                'class'=>'form-control'  
            )))
            ->add('image',FileType::Class,[
                'label'=>false, 
                    'mapped'=>false
                ]
           )
           ->add('telephone',  IntegerType::class, array(
            'label'=>false, 
            'constraints' => 
                    new Length([
                        'min' => 8,
                        'minMessage' => "Le numéro du telephone doit avoir 8 chiffre",
                    
                       
                    ]),
            'attr' => array(
                'placeholder' => 'Numéro de téléphone ','class'=>'form-control'
            )
       ))
        ->add('nom',  TextType::class, array(
            'label'=>false, 
            'attr' => array(
                'placeholder' => '  Nom','class'=>'form-control'
            )
       ))
        ->add('prenom',  TextType::class, array(
            'label'=>false, 
           
            'attr' => array(
                'placeholder' => '  Prenom',
                'class'=>'form-control'
            )
       ))
            ->add('cin',  IntegerType::class, array(
                'label'=>false, 
                'constraints' => 
                    new Length([
                        'min' => 8,
                        'minMessage' => "Carte d'identité doit avoir 8 chiffre",
                    
                      
                    ]),
                'attr' => array(
                    'placeholder' => "Carte d'identité"
                    ,'class'=>'form-control'
                )))
                ->add('adresse',TextType::class, array(
                    'label'=>false, 
                    'attr' => array(
                        'placeholder' => 'Adresse'
                        ,'class'=>'form-control'
                    )))
            ->add('roles', ChoiceType::class, [
                'label'=>false, 
                'multiple'=>true,
                
                    'choices'  => [
                        
                        'Employé' => "ROLE_USERS",
                        'Responsable RH' => "ROLE_Rh",
                                          
                    ],
                ])

            ->add('password', RepeatedType::class, array(
                'label'=>false, 
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'required' => true,
                'first_options'  => array('label' => false),
                'second_options' => array('label' => false),
                'constraints' => 
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit avoir au minimum 8 caractére',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ))
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rh::class,
            "allow_extra_fields" => true
        ]);
    }
}
