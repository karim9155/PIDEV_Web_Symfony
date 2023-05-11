<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('status')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'payer' => 'payer',
                    'non payer' => 'non payer',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('prix')
            ->add('nom_ticket')
            ->add(
                'id_reservation',
                EntityType::class,
                [
                    'class' => Reservation::class,
                    'choice_label' => 'id'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
