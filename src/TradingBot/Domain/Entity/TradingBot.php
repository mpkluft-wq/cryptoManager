<?php

declare(strict_types=1);

namespace App\TradingBot\Domain\Entity;

class TradingBot
{
    private int $id;

    public function __construct(
        private readonly string $botName,
        private readonly int $exchangeId,
        private readonly int $tradingPairBaseId,
        private readonly int $tradingPairQuoteId,
        private readonly string $rangePriceFrom,
        private readonly string $rangePriceTo,
        private readonly int $gridCount,
        private readonly string $step
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getBotName(): string
    {
        return $this->botName;
    }

    public function getExchangeId(): int
    {
        return $this->exchangeId;
    }

    public function getTradingPairBaseId(): int
    {
        return $this->tradingPairBaseId;
    }

    public function getTradingPairQuoteId(): int
    {
        return $this->tradingPairQuoteId;
    }

    public function getRangePriceFrom(): string
    {
        return $this->rangePriceFrom;
    }

    public function getRangePriceTo(): string
    {
        return $this->rangePriceTo;
    }

    public function getGridCount(): int
    {
        return $this->gridCount;
    }

    public function getStep(): string
    {
        return $this->step;
    }
}
