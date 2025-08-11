<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BybitClient
{
    private HttpClientInterface $http;
    private string $apiKey;
    private string $apiSecret;
    private int $recvWindow = 20000;

    public function __construct(
        HttpClientInterface $httpClient,
        string $apiKey,
        string $apiSecret
    ) {
        $this->http = $httpClient;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * Делает запрос к API Bybit V5
     *
     * @param string $method  HTTP-метод: 'GET' или 'POST'
     * @param string $uri     Относительный путь, например "/order/create"
     * @param array  $params  Параметры запроса
     *
     * @return array  Ответ разбирается в ассоциативный массив
     */
    public function request(string $method, string $uri, array $params = []): array
    {
        $timestamp = (int) round(microtime(true) * 1000);
        $signature = $this->buildBybitSignature(
            $this->apiSecret,
            $this->apiKey,
            $this->recvWindow,
            $timestamp,
            $params,
            $method
        );

        $headers = [
            'X-BAPI-API-KEY'    => $this->apiKey,
            'X-BAPI-SIGN'       => $signature,
            'X-BAPI-SIGN-TYPE'  => '2',
            'X-BAPI-TIMESTAMP'  => $timestamp,
            'X-BAPI-RECV-WINDOW'=> $this->recvWindow,
            'Content-Type'      => 'application/json',
        ];

        $options = [
            'headers' => $headers,
        ];

        if (strtoupper($method) === 'POST') {
            $options['json'] = $params;
        } else {
            $options['query'] = $params;
        }

        try {
            $response = $this->http->request($method, 'https://api.bybit.com/v5'.$uri, $options);
            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Bybit request failed: '.$e->getMessage());
        }
    }

    private function buildBybitSignature(
        string $apiSecret,
        string $apiKey,
        int $recvWindow,
        int $timestamp,
        array $params = [],
        string $method = 'GET'
    ): string {
        if (strtoupper($method) === 'POST') {
            $body   = json_encode($params, JSON_UNESCAPED_UNICODE);
            $raw    = $timestamp . $apiKey . $recvWindow . $body;
        } else {
            ksort($params);
            $qs     = http_build_query($params);
            $raw    = $timestamp . $apiKey . $recvWindow . $qs;
        }

        return hash_hmac('sha256', $raw, $apiSecret);
    }
}
