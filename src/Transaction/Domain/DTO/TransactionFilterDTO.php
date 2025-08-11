<?php

declare(strict_types=1);

namespace App\Transaction\Domain\DTO;

/**
 * DTO параметров фильтрации и пагинации для транзакций
 */
class TransactionFilterDTO
{
    private ?string $exchangeId = null;
    private ?string $tradingBotId = null;
    private int $offset = 0;
    private int $limit = 10;
    /** @var array<string, string> */
    private array $sort = ['timestamp' => 'DESC'];

    /**
     * @param string|null $exchangeId
     * @param string|null $tradingBotId
     * @param int|null $offset
     * @param int|null $limit
     * @param array<string, string>|null $sort
     */
    public function __construct(
        ?string $exchangeId = null,
        ?string $tradingBotId = null,
        ?int $offset = null,
        ?int $limit = null,
        ?array $sort = null
    ) {
        if ($exchangeId !== null) {
            $this->exchangeId = $exchangeId;
        }
        if ($tradingBotId !== null) {
            $this->tradingBotId = $tradingBotId;
        }
        if ($offset !== null && $offset >= 0) {
            $this->offset = $offset;
        }
        if ($limit !== null && $limit > 0) {
            $this->limit = $limit;
        }
        if ($sort !== null && !empty($sort)) {
            $this->sort = $sort;
        }
    }

    public function getExchangeId(): ?string
    {
        return $this->exchangeId;
    }

    public function hasExchangeId(): bool
    {
        return $this->exchangeId !== null && $this->exchangeId !== '';
    }

    public function getTradingBotId(): ?string
    {
        return $this->tradingBotId;
    }

    public function hasTradingBotId(): bool
    {
        return $this->tradingBotId !== null && $this->tradingBotId !== '';
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return array<string, string>
     */
    public function getSort(): array
    {
        return $this->sort;
    }
}
