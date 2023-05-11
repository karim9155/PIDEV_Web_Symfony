<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserState;
use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class AdminEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'disabled' => true])
            ->add('prenom',TextType::class, [
                'disabled' => true])
            ->add('username',TextType::class, [
                'disabled' => true])
            ->add('email',TextType::class, [
                'disabled' => true])
            ->add('password',TextType::class, [
                'disabled' => true])
            ->add('num_tel',TextType::class, [
                'disabled' => true])
            ->add('CIN',TextType::class, [
                'disabled' => true])
            ->add('image',TextType::class, [
                'disabled' => true])
            ->add('id_role',EntityType::class,
            ['class'=>Role::class,
            'choice_label'=>'nom',
            'disabled' => true])
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
