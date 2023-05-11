<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
class RegisterType extends AbstractType
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
                ],
            ])
            ->add('email',EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez saisir votre adresse e-mail']),
                    new Email(['message' => 'Vous devez saisir une adresse e-mail valide']),
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
                ],
            ])
            ->add('images',FileType::class,[
                'required'=>false,
                'mapped'=>false,
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
            'data_class' => User::class,
        ]);
    }
}
