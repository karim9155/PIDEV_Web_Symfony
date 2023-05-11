<?php

namespace App\Form;

use App\Entity\MoyenTransport;
use App\Entity\Ligne;
use App\Entity\Station;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoyenTransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('num')
            ->add('capacite')
            ->add('type_vehicule')
            ->add('marque')
            ->add('etat')
            ->add('station',EntityType::class,
            ['class'=>Station::class,
            'choice_label'=>'id'])
            ->add('id_ligne',EntityType::class,
            ['class'=>Ligne::class,
            'choice_label'=>'nom_ligne'])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MoyenTransport::class,
        ]);
    }
}
