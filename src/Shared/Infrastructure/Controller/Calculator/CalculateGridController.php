<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Calculator;

use App\Shared\Infrastructure\Form\Calculator\CalculateGridType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Скрипт расчёта торговой сетки
 *
 * ТЗ (сохранено по требованию):
 * 📄 Техническое задание: Скрипт расчёта торговой сетки
 * 🧭 Назначение
 * Скрипт рассчитывает уровни покупки и продажи актива по сетке, чтобы при полной продаже достичь заданной цели — например, x3 от вложенного капитала. Покупки происходят при просадке, продажи — при росте. Объёмы операций задаются в процентах.
 *
 * 📥 Входные данные
 * Параметр	Описание
 * average_price	Средняя цена покупки актива
 * initial_capital	Стартовый капитал (в валюте)
 * target_multiplier	Целевая прибыль (например, 3 для x3)
 * drop_step_percent	Шаг просадки для покупки (например, 5%)
 * buy_percent	Процент капитала, используемый на каждую докупку (например, 10%)
 * rise_step_percent	Шаг роста для продажи (например, 10%)
 * precision	Точность округления цен и объёмов — до 4 знаков после запятой
 * Также: total_asset_amount — общий объём актива для продаж (уточнение из требований).
 *
 * 📤 Выходные данные
 * Массив уровней покупки (price, amount, capital_used)
 * Массив уровней продажи (price, amount, expected_profit)
 * Метаданные: общая сумма капитала, использованного на покупки; общий объём актива, проданный по сетке; общая прибыль по факту продажи.
 *
 * 🧮 Логика работы
 * Покупка: начинается ниже average_price, первый уровень = average_price * (1 - drop_step_percent/100). На каждом уровне тратится buy_percent от оставшегося капитала. Останов при достижении max_drawdown_percent.
 * Продажа: начинается выше average_price, первый уровень = average_price * (1 + rise_step_percent/100). На каждом уровне продаётся доля от общего объёма, рассчитанная автоматически: старт 1% и дальнейший рост геометрически с множителем (1 + rise_step_percent/100). Продажи ведутся до достижения целевой выручки (average_price * total_asset_amount * target_multiplier).
 * Прибыль: сумма выручки минус капитал, потраченный на покупки. Комиссии не учитываются. Округление по precision.
 */
final class CalculateGridController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CalculateGridType::class);
        $form->handleRequest($request);

        $result = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $result = $this->calculate($data);
        }

        return $this->render('/calculator/calculate_grid.html.twig', [
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function calculate(array $data): array
    {
        $avg = (float) $data['average_price'];
        $targetMult = (int) $data['target_multiplier'];
        $riseStep = (float) $data['rise_step_percent'];
        $precision = (int) $data['precision'];
        $totalAsset = (float) $data['total_asset_amount'];

        $sellLevels = [];

        // Цель по выручке: оценочная стоимость всего объёма по средней цене * мультипликатор
        $targetRevenue = $avg * $totalAsset * $targetMult;
        $accRevenue = 0.0;

        // Доля продаж по уровням рассчитывается автоматически:
        // - стартуем с min 1% от общего объёма на первом уровне
        // - на каждом следующем уровне доля растёт геометрически с множителем (1 + rise_step/100)
        $percentThisLevel = 1.0; // % от общего объёма на первом уровне (минимум)
        $growthFactor = 1.0 + ($riseStep / 100.0);

        $sellLevelIndex = 1;
        $sellPrice = $avg * (1 + $riseStep / 100.0);
        $totalSold = 0.0;
        $remainingAsset = $totalAsset;

        // Защита от бесконечного цикла
        $maxLevels = 1000;
        $iterations = 0;

        while ($accRevenue < $targetRevenue && $iterations < $maxLevels && $remainingAsset > 0) {
            // Рассчитываем объём к продаже на уровне из процента (от общего), но не больше остатка
            $amountPlanned = $totalAsset * ($percentThisLevel / 100.0);
            $amountToSell = min($amountPlanned, $remainingAsset);
            if ($amountToSell <= 0) {
                break;
            }

            $revenue = $amountToSell * $sellPrice;

            $sellLevels[] = [
                'level' => $sellLevelIndex,
                'price' => $this->r($sellPrice, $precision),
                'percent' => $this->r($percentThisLevel, 2),
                'amount' => $this->r($amountToSell, $precision),
                'expected_profit' => $this->r($revenue, $precision),
            ];

            $accRevenue += $revenue;
            $totalSold += $amountToSell;
            $remainingAsset = $this->r($remainingAsset - $amountToSell, $precision);

            $sellLevelIndex++;
            $sellPrice = $sellPrice * (1 + $riseStep / 100.0);
            // Увеличиваем процент на следующий уровень геометрически
            $percentThisLevel = max(1.0, $percentThisLevel * $growthFactor);
            $iterations++;
        }

        $accRevenue = $this->r($accRevenue, $precision);
        $totalSold = $this->r($totalSold, $precision);

        return [
            'sell_levels' => $sellLevels,
            'meta' => [
                'total_sold_amount' => $totalSold,
                'target_revenue' => $this->r($targetRevenue, $precision),
                'reached_target' => $accRevenue >= $targetRevenue,
            ],
        ];
    }

    private function r(float $value, int $precision): float
    {
        return round($value, $precision);
    }
}
