<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Infrastructure\Client\BybitClient;
use App\TradingBot\Domain\Entity\TradingBot;
use App\TradingBot\Domain\Repository\TradingBotRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiBybitController extends AbstractController
{
    public function __construct(
        private readonly BybitClient $bybitClient,
        private readonly TradingBotRepositoryInterface $tradingBotRepository,
    ) {
    }

    #[Route('/api/addBot')]
    public function number1(): Response
    {
        $this->tradingBotRepository->add(
            new TradingBot(
                botName: 'newBot',
                exchangeId: 1,
                tradingPairBaseId: 1,
                tradingPairQuoteId: 1,
                rangePriceFrom: '3.22',
                rangePriceTo: '3.42',
                gridCount: 10,
                step: '0.55',
            )
        );

        return new Response();
    }

    #[Route('/api/bit')]
    public function number(): Response
    {
        $bybitsignature = $this->buildBybitSignature(
            apiSecret: 'pV9OLPabQKUbijKF42Nf30u8p0TV6li4ufGG',
            apiKey: '4pN8PJp3W3JLJaxANC',
            recvWindow: 5000,
            timestamp: (new \DateTimeImmutable())->getTimestamp(),
            params: [],
            method: 'GET',
        );

        $this->bybitClient->request('GET', '/v5/asset/transfer/query-asset-info?accountType=SPOT');

        return new Response($bybitsignature);
    }

    #[Route('/bybit/spot-assets', name: 'bybit_spot_assets')]
    public function querySpotAssets(): JsonResponse
    {
        $params = ['accountType' => 'SPOT'];

        try {
            $result = $this->bybitClient->request('GET', '/asset/transfer/query-asset-info', $params);
            return $this->json($result);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/bybit/account-balance', name: 'bybit_account_balance')]
    public function getAccountBalance(): JsonResponse
    {
        $params = ['accountType' => 'SPOT'];
        try {
            $result = $this->bybitClient->request(
                'GET',
                '/asset/transfer/query-account-coins-balance',
                $params,
            );
            return $this->json($result);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/bybit/withdraw-history', name: 'bybit_withdraw_history')]
    public function getWithdrawHistory(): JsonResponse
    {
        try {
            $result = $this->bybitClient->request('GET', '/asset/withdraw/query-record', []);
            return $this->json($result);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Собирает подпись для Bybit API V5
     *
     * @param string $apiSecret   Секретный ключ
     * @param string $apiKey      Публичный API Key
     * @param int    $recvWindow  Окно приема (ms)
     * @param int    $timestamp   Время в миллисекундах
     * @param array  $params      Параметры запроса
     * @param string $method      HTTP-метод ('GET' или 'POST')
     *
     * @return string Подпись в lowercase hex
     */
    function buildBybitSignature(
        string $apiSecret,
        string $apiKey,
        int $recvWindow,
        int $timestamp,
        array $params = [],
        string $method = 'GET'
    ): string {
        if (strtoupper($method) === 'POST') {
            // Для POST включаем JSON-тело
            $body = json_encode($params, JSON_UNESCAPED_UNICODE);
            $raw = $timestamp . $apiKey . $recvWindow . $body;
        } else {
            // Для GET — строка параметров в порядке ключей
            ksort($params);
            $queryString = http_build_query($params);
            $raw = $timestamp . $apiKey . $recvWindow . $queryString;
        }

        // HMAC-SHA256 и hex lowercase
        return hash_hmac('sha256', $raw, $apiSecret);
    }
}