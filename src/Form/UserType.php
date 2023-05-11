<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserState;
use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
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
use Symfony\Component\Validator\Validation;

class UserType extends AbstractType
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
                /*new UniqueEntity([
                    'entityClass'=> User::class,
                    'fields' => ['username'],
                    'message' => 'username existe déjà.',
                ])*/
                

            ],
            
        ])
        ->add('email',EmailType::class, [
            'constraints' => [
                new NotBlank(['message' => 'adresse e-mail obligatoire']),
                new Email(['message' => 'adresse e-mail valide non valide']),
            ],
        ])
            ->add('password',PasswordType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ est obligatoire']),
                    new Length(['min' => 8, 'max' => 30,
                    'minMessage' => 'Le mot de passe doit au moins contenir 8 caractéres',
                    'maxMessage' => 'le mot de passe ne doit pas dépasser 30 caractéres',]),
                ]
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
            ->add('id_role',EntityType::class,
            ['class'=>Role::class,
            'choice_label'=>'nom'])
            ->add('id_state',EntityType::class,
            ['class'=>UserState::class,
            'choice_label'=>'status'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
    
}
