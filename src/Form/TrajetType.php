<?php

namespace App\Form;

use App\Entity\Trajet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Iteneraire;
class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temps_parcours')
            ->add('pts_depart')
            ->add('pts_arrive')
            ->add('id_it',EntityType::class,
            ['class'=>Iteneraire::class,
            'choice_label'=> function($iteneraire) {
                return $iteneraire->getPtsDepart() . ' - ' . $iteneraire->getPtsArrive();
            }])

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
