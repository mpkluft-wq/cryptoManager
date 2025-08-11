<?php

namespace App\Portfolio\Domain\Entity;

use App\CryptoCurrency\Domain\Entity\CryptoCurrency;
use DateTimeImmutable;

/**
 * Класс сущности записи портфеля криптоактивов.
 * 
 * Описывает остаток по конкретному активу, его среднюю и текущую цену.
 */
class CryptoPortfolio
{
    /**
     * Уникальный идентификатор записи портфеля.
     */
    private ?int $id = null;

    /**
     * Связанная криптовалюта (FK на crypto_currency.id).
     */
    private CryptoCurrency $cryptoCurrency;

    /**
     * Количество актива (DECIMAL(36,18) — хранится как строка для точности).
     */
    private string $amount;

    /**
     * Средняя цена (DECIMAL(36,18) — хранится как строка для точности).
     */
    private string $averagePrice;

    /**
     * Текущая цена (DECIMAL(36,18) — хранится как строка для точности).
     */
    private string $currentPrice;

    /**
     * Дата создания (устанавливается автоматически при сохранении).
     */
    private DateTimeImmutable $createdAt;

    /**
     * Дата обновления (обновляется автоматически при изменениях).
     */
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Конструктор.
     */
    public function __construct(
        CryptoCurrency $cryptoCurrency,
        string $amount,
        string $averagePrice,
        string $currentPrice
    ) {
        $this->cryptoCurrency = $cryptoCurrency;
        $this->amount = $amount;
        $this->averagePrice = $averagePrice;
        $this->currentPrice = $currentPrice;
        // createdAt будет установлен в prePersist()
    }

    // Getters/Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCryptoCurrency(): CryptoCurrency
    {
        return $this->cryptoCurrency;
    }

    public function setCryptoCurrency(CryptoCurrency $cryptoCurrency): void
    {
        $this->cryptoCurrency = $cryptoCurrency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getAveragePrice(): string
    {
        return $this->averagePrice;
    }

    public function setAveragePrice(string $averagePrice): void
    {
        $this->averagePrice = $averagePrice;
    }

    public function getCurrentPrice(): string
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(string $currentPrice): void
    {
        $this->currentPrice = $currentPrice;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Метод для установки временных меток перед сохранением.
     */
    public function prePersist(): void
    {
        $now = new DateTimeImmutable('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /**
     * Метод для обновления отметки времени при изменении.
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable('now');
    }
}
