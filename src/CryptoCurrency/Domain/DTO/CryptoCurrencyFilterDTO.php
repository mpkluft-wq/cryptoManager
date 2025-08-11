<?php

declare(strict_types=1);

namespace App\CryptoCurrency\Domain\DTO;

class CryptoCurrencyFilterDTO
{
    private array $ids = [];
    private int $offset = 0;
    private int $limit = 10;
    private array $sort = ['id' => 'ASC'];

    public function __construct(
        ?array $ids = null,
        ?int $offset = null,
        ?int $limit = null,
        ?array $sort = null
    ) {
        if ($ids !== null) {
            $this->ids = $ids;
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

    public function getIds(): array
    {
        return $this->ids;
    }

    public function hasIds(): bool
    {
        return !empty($this->ids);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getSort(): array
    {
        return $this->sort;
    }
}