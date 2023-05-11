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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClientType extends AbstractType
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
