<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Form\Calculator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

final class CalculateGridType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('average_price', NumberType::class, [
                'label' => 'Средняя цена актива',
                'scale' => 8,
                'constraints' => [new GreaterThan(['value' => 0])],
            ])
            ->add('total_asset_amount', NumberType::class, [
                'label' => 'Общий объём актива (для продаж)',
                'scale' => 8,
                'constraints' => [new GreaterThan(['value' => 0])],
            ])
            ->add('target_multiplier', ChoiceType::class, [
                'label' => 'Целевая прибыль (мультипликатор)',
                'choices' => [
                    'x2' => 2,
                    'x3' => 3,
                    'x4' => 4,
                    'x5' => 5,
                    'x6' => 6,
                ],
            ])
            ->add('rise_step_percent', NumberType::class, [
                'label' => 'Шаг роста для продажи (%)',
                'scale' => 4,
                'constraints' => [new GreaterThan(['value' => 0])],
                'help' => 'Проценты продажи по уровням будут рассчитаны автоматически и будут расти геометрически с шагом роста. Минимум 1% на первом уровне.',
            ])
            ->add('precision', IntegerType::class, [
                'label' => 'Точность округления',
                'data' => 4,
                'constraints' => [new GreaterThanOrEqual(['value' => 0])],
            ])
            ->add('calculate', SubmitType::class, [
                'label' => 'Рассчитать сетку',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}
