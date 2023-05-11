<?php
/*
namespace App\Form;

use App\Entity\User;
use App\Entity\UserState;
use App\Entity\Role;
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProfileType extends AbstractType
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
                new UniqueEntity([
                    'entityClass'=> User::class,
                    'fields' => ['email'],
                    'message' => 'adresse email existe déjà.',
                ])
            ],
        ])
        ->add('password',PasswordType::class,[
            'required' => false,
            'constraints' => [

                new NotBlank(['message' => 'Vous devez entrer votre ancien mot de passe']),
                new Length(['min' => 8, 'max' => 30,
                'minMessage' => 'Le mot de passe doit au moins contenir 8 caractéres',
                'maxMessage' => 'le mot de passe ne doit pas dépasser 30 caractéres',]),
            ]
        ])
        ->add('newPassword', PasswordType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new Length([
                    'min' => 8,
                    'max' => 30,
                    'minMessage' => 'Le mot de passe doit au moins contenir 8 caractéres',
                    'maxMessage' => 'le mot de passe ne doit pas dépasser 30 caractéres',
                ]),
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
                new UniqueEntity([
                    'entityClass'=>User::class,
                    'fields' => ['cin'],
                    'message' => 'numéro carte identité existe déjà.',
                ])
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
            'empty_data' => " ",
            'data_class' => User::class,
        ]);
    }
}
*/
namespace App\Form;

use App\Entity\User;
use App\Entity\UserState;
use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\File;
class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'nom obligatoire']),
                ],
            ])
            ->add('prenom',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'prénom obligatoire']),
                ],
            ])
            ->add('username',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'username obligatoire']),
                ],
            ])
            ->add('email',EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'adresse e-mail obligatoire']),
                    new Email(['message' => 'adresse e-mail valide non valide']),
                ],
            ])
            ->add('num_tel',NumberType::class,[
                'constraints'=>[
                    new NotBlank(['message' => 'numéro de téléphone obligatoire']),
                    new Regex(['pattern'=>'/^\d{8}$/','message' => 'numéro de téléphone non valide']),
                ],
            ])
            ->add('CIN',NumberType::class,[
                'constraints'=>[
                    new NotBlank(['message' => 'numéro de carte identité obligatoire']),
                    new Regex(['pattern'=>'/^\d{8}$/','message' => 'numéro de carte identité non valide']),
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
            'data_class' => User::class,
        ]);
    }

}
