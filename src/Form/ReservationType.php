<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Iteneraire;
use App\Entity\MoyenTransport;
use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('date_reservation')
            ->add('date_reservation',DateType::class, array(
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'data' => new \DateTime(),))

            /*->add('date_reservation',ChoiceType::class,[
                    'choices' => [
                        'now' => new \DateTime('now'),
                        'tomorrow' => new \DateTime('+1 day'),
                        '1 week' => new \DateTime('+1 week'),
                        '1 month' => new \DateTime('+1 month'),
                    ],
                    'group_by' => function($choice, $key, $value) {
                        if ($choice <= new \DateTime('+3 days')) {
                            return 'Soon';
                        }
    
                        return 'Later';
                    },
                ])*/
            //->add('heure_depart')
            ->add('heure_depart', TimeType::class, [
                'widget' => 'single_text',
                'input_format' => 'H:i', 
                'attr' => [
                    'class' => 'timepicker', 
                ],
            ])
            
            //->add('heure_arrive')
            ->add('heure_arrive', TimeType::class, [
                'widget' => 'single_text',
                'input_format' => 'H:i', // specify the input format
                'attr' => [
                    'class' => 'timepicker', // add a class for styling the timepicker
                ],
            ])
            ->add('status')
            ->add('type_ticket', ChoiceType::class, [
                'placeholder' => 'choisissez le type de billet qui vous convient...',
                'choices' => [
                    'Ticket aller simple bus' => 'aller simple bus',
                    'Ticket aller retour bus' => 'aller retour bus',
                    'Ticket aller simple métro' => 'aller simple métro',
                    'Ticket aller retour métro' => 'aller retour métro',
                    'Ticket aller simple train' => 'aller simple train',
                    'Ticket aller retour train' => 'aller retour train',
                ],
            ])
            
            ->add('id_client' , EntityType::class,
            ['class'=>User::class,
            'choice_label'=>'nom'])
            
            ->add('id_moy',EntityType::class,
            ['class'=>MoyenTransport::class,
            'choice_label'=>'type_vehicule'])

            ->add('id_it', EntityType::class,
            ['class'=>Iteneraire::class,
            'choice_label'=> function($iteneraire) {
                return $iteneraire->getPtsDepart() . ' - ' . $iteneraire->getPtsArrive();
            }])
            ->add('id_ticket',EntityType::class,
            ['class'=>Ticket::class,
            'choice_label'=>'id'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
