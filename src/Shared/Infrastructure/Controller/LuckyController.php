<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $amount = 200;
        $countGrid = 10;
        $currentPrice = 10; // стоимость в usdt одного TON
        $rangePriceFrom = 5;
        $rangePriceTo = 15;
        $allCryptoAmount = $amount / $currentPrice;
        $singleCryptoBet = $allCryptoAmount / $countGrid;


        return $this->render(
            '/lucky_number.html.twig',
            [
                'amount' => $amount,
                'countGrid' => $countGrid,
                'currentPrice' => $currentPrice,
                'rangePriceFrom' => $rangePriceFrom,
                'rangePriceTo' => $rangePriceTo,
                'allCryptoAmount' => $allCryptoAmount,
                'singleCryptoBet' => $singleCryptoBet,
                'firstOrdersToSell' => $this->getFirstOrdersToSell($currentPrice, $rangePriceFrom, $rangePriceTo, $countGrid, $singleCryptoBet),
                'firstOrdersToBuy' => $this->getFirstOrdersToBuy($currentPrice, $rangePriceFrom, $rangePriceTo, $countGrid, $singleCryptoBet),
            ],
        );
    }

    private function getFirstOrdersToSell(int $currentPrice, int $rangePriceFrom, int $rangePriceTo, $countGrid, float|int $singleCryptoBet): array
    {
        $result = [];
        $step = ($rangePriceTo - $rangePriceFrom)/$countGrid;
        for ($i = $rangePriceFrom; $i <= $rangePriceTo;$i+=$step) {
            $resultStep = [];
            if ($i <= $currentPrice) {
                continue;
            }

            $resultStep['buy'] = $currentPrice;
            $resultStep['sell'] = $i;
            $resultStep['amount'] = $singleCryptoBet;
            $resultStep['profit'] = (($i - $currentPrice)*$singleCryptoBet) . ' usdt';
            $result[] = $resultStep;
        }
        return $result;
    }

    private function getFirstOrdersToBuy(int $currentPrice, int $rangePriceFrom, int $rangePriceTo, int $countGrid, float|int $singleCryptoBet): array
    {
        $result = [];
        $step = ($rangePriceTo - $rangePriceFrom)/$countGrid;
        for ($i = $rangePriceFrom; $i <= $rangePriceTo;$i+=$step) {
            $resultStep = [];
            if ($i > $currentPrice) {
                break;
            }

            $resultStep['buy'] = $i;
            $resultStep['sell'] = $i + $step;
            $resultStep['amount'] = $singleCryptoBet;
            $resultStep['profit'] = ($step * $singleCryptoBet) . ' usdt';
            $result[] = $resultStep;
        }
        return $result;
    }
}