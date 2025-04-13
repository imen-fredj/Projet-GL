<?php

namespace App\Form;

use App\Entity\Rh;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ModificationProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [   
                'label' => false,
                'disabled' => true,
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'  
                ]
            ])
        
            ->add('telephone', IntegerType::class, [
                'label' => false,
                'constraints' => new Length([
                    'min' => 8,
                    'minMessage' => "Le numéro du telephone doit avoir 8 chiffres",
                ]),
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                    'class' => 'form-control'
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control'
                ]
            ])
            ->add('cin', IntegerType::class, [
                'label' => false,
                'disabled' => true,
                'constraints' => new Length([
                    'min' => 8,
                    'minMessage' => "Carte d'identité doit avoir 8 chiffres",
                ]),
                'attr' => [
                    'placeholder' => "Carte d'identité",
                    'class' => 'form-control'
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse',
                    'class' => 'form-control'
                ]
            ])
       
            // ...
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rh::class,
            "allow_extra_fields" => true
        ]);
    }
}