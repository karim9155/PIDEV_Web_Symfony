<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        

        $builder
        ->add('nom',TextType::class,[
            'constraints' => [
                new NotBlank(['message' => 'Vous devez saisir votre nom']),
            ],
        ])
        ->add('prenom',TextType::class,[
            'constraints' => [
                new NotBlank(['message' => 'Vous devez saisir votre prénom']),
            ],
        ])
        ->add('username',TextType::class,[
            'constraints' => [
                new NotBlank(['message' => 'Vous devez saisir votre username']),
                /*new UniqueEntity([
                    'entityClass'=> User::class,
                    'fields' => ['username'],
                    'message' => 'username existe déjà.',
                ])*/
                

            ],
        ])
        ->add('email',EmailType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Vous devez saisir votre adresse e-mail']),
                new Email(['message' => 'Vous devez saisir une adresse e-mail valide']),
                /*new UniqueEntity([
                    'entityClass'=> User::class,
                    'fields' => ['email'],
                    'message' => 'adresse email existe déjà.',
                ])*/
            ],
        ])
        ->add('password',RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'les mots de passe ne correspondent pas',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmer mot de passe'],
            'constraints' => [
                new NotBlank(['message' => 'Ce champ est obligatoire']),
                new Length(['min' => 8, 'max' => 30,
                'minMessage' => 'Votre mot de passe doit au moins contenir 8 caractéres',
                'maxMessage' => 'Votre mot de passe ne doit pas dépasser 30 caractéres',]),
            ],
        ])
        
    
        ->add('num_tel',NumberType::class,[
            'constraints'=>[
                new NotBlank(['message' => 'Vous devez saisir votre numéro de téléphone']),
                new Regex(['pattern'=>'/^\d{8}$/','message' => 'Vous devez saisir un numéro de téléphone valide']),
            ],
        ])
        ->add('CIN',NumberType::class,[
            'constraints'=>[
                new NotBlank(['message' => 'Vous devez saisir votre numéro de carte identité']),
                new Regex(['pattern'=>'/^\d{8}$/','message' => 'Vous devez saisir un numéro de carte identité valide']),
                /*new UniqueEntity([
                    'entityClass'=>User::class,
                    'fields' => ['cin'],
                    'message' => 'numéro carte identité existe déjà.',
                ])*/
            ],
        ])
        ->add('images',FileType::class,[
            'required'=>false,
            'mapped'=>false,
            'attr' => ['accept' => 'image/jpeg, image/png ,image/jpg'],
            'constraints'=>
            [new File([
                'maxSize'=>'1024K',
                'mimeTypes'=>[
                   'image/jpeg',
                   'image/png',
                   'image/jpg'
        ],
        'mimeTypesMessage'=> 'vous devez choisir une image valide',
    ]),
],
])

    ;
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

}
