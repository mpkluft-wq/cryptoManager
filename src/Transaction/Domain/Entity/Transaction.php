<?php

declare(strict_types=1);

namespace App\Transaction\Domain\Entity;

use App\Shared\Domain\Service\UlidService;
use App\Transaction\Domain\Enumeration\TransactionType;

/**
 * Сущность транзакции в системе.
 * Хранит информацию о финансовых операциях: покупка/продажа, ввод/вывод средств и т.д.
 */
class Transaction
{
    /**
     * Идентификатор транзакции (ULID как строка)
     */
    private readonly string $id;

    public function __construct(
        private readonly \DateTimeImmutable $timestamp,
        private readonly TransactionType $type,
        private readonly string $exchangeId,
        private readonly ?string $tradingBotId,
        private readonly string $assetSymbol,
        private readonly float $quantity,
        private readonly float $pricePerUnit,
        private readonly float $totalAmount,
        private readonly string $currency,
        private readonly ?string $externalTransactionId,
        private readonly ?string $notes
    ) {
        $this->id = UlidService::generate();
    }

    /**
     * Возвращает идентификатор транзакции
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Момент времени, когда произошла транзакция
     */
    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * Тип транзакции
     */
    public function getType(): TransactionType
    {
        return $this->type;
    }

    /**
     * Идентификатор биржи
     */
    public function getExchangeId(): string
    {
        return $this->exchangeId;
    }

    /**
     * Идентификатор торгового бота (если применимо)
     */
    public function getTradingBotId(): ?string
    {
        return $this->tradingBotId;
    }

    /**
     * Символ актива (например, BTC, ETH)
     */
    public function getAssetSymbol(): string
    {
        return $this->assetSymbol;
    }

    /**
     * Количество актива
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * Цена за единицу актива
     */
    public function getPricePerUnit(): float
    {
        return $this->pricePerUnit;
    }

    /**
     * Общая сумма операции
     */
    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    /**
     * Валюта расчета (например, USD, USDT)
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Внешний идентификатор транзакции (например, ID из биржи)
     */
    public function getExternalTransactionId(): ?string
    {
        return $this->externalTransactionId;
    }

    /**
     * Примечания
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
