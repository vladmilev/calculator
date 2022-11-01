<?php

namespace App\Form;

use App\DTO\CalculatorData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalculatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numberOne',
                IntegerType::class,
                [
                    'label' => 'Enter the number',
                    'required' => true,
                ]
            )
            ->add('operation',
                ChoiceType::class, array(
                    'choices' => array(
                        '+' => '+',
                        '-' => '-',
                        '*' => '*',
                        '/' => '/'
                    )
                )
            )
            ->add('numberTwo',
                IntegerType::class,
                [
                    'label' => 'Enter the number',
                    'required' => true,
                ]
            )
            ->add('gotoCache',
                HiddenType::class)
            ->add('doneCache',
                HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Выполнить'
            ])
            ->add('submitToCache', SubmitType::class, [
                'label' => 'Добавить в очередь'
            ])
            ->add('submitDoneCache', SubmitType::class, [
                'label' => 'Выполнить все в очереди'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CalculatorData::class,
        ]);
    }
}
