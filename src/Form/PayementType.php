<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\CardScheme;


class PayementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('card_number')
            ->add(
                'exp_month',
                null,
                ['constraints' => [new Callback(function ($value, ExecutionContextInterface $context) use ($options) {
                    $form = $context->getRoot();
                    $year = $form->get('exp_year')->getData();
                    $expirationDate = \DateTime::createFromFormat('Y-m', sprintf('%d-%02d', $year, $value));
                    $now = new \DateTime();
                    if ($expirationDate < $now) {
                        $context->buildViolation('The expiration date cannot be in the past')
                            ->atPath('exp_month')
                            ->addViolation();
                    }
                })]]
            )
            ->add(
                'exp_year',
                null,
                [
                    'constraints' =>
                    [
                        new Callback(function ($value, ExecutionContextInterface $context) use ($options) {
                            $form = $context->getRoot();
                            $month = $form->get('exp_month')->getData();
                            $expirationDate = \DateTime::createFromFormat('Y-m', sprintf('%d-%02d', $value, $month));
                            $now = new \DateTime();
                            if ($expirationDate < $now) {
                                $context->buildViolation('The expiration date cannot be in the past')
                                    ->atPath('exp_year')
                                    ->addViolation();
                            }
                        })
                    ]
                ]
            )
            ->add('cvc')
            ->add('confirm_paiement', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
