<?php

declare(strict_types=1);

namespace App\Portfolio\Domain\DTO;

/**
 * DTO параметров фильтрации и пагинации для записей CryptoPortfolio
 */
class CryptoPortfolioFilterDTO
{
    /**
     * Список ID криптовалют для фильтрации. Если пуст, не применяется.
     * @var array<int>
     */
    private array $cryptoCurrencyIds = [];

    private int $offset = 0;
    private int $limit = 10;
    /** @var array<string, string> */
    private array $sort = ['id' => 'ASC'];

    /**
     * @param array<int>|null $cryptoCurrencyIds
     * @param int|null $offset
     * @param int|null $limit
     * @param array<string,string>|null $sort
     */
    public function __construct(
        ?array $cryptoCurrencyIds = null,
        ?int $offset = null,
        ?int $limit = null,
        ?array $sort = null
    ) {
        if ($cryptoCurrencyIds !== null) {
            // Оставляем только целые положительные значения
            $this->cryptoCurrencyIds = array_values(array_filter($cryptoCurrencyIds, static fn($v) => is_int($v) && $v > 0));
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

    /**
     * @return array<int>
     */
    public function getCryptoCurrencyIds(): array
    {
        return $this->cryptoCurrencyIds;
    }

    public function hasCryptoCurrencyIds(): bool
    {
        return !empty($this->cryptoCurrencyIds);
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
