<?php

declare(strict_types=1);

namespace App\Portfolio\Domain\Repository;

use App\Portfolio\Domain\DTO\CryptoPortfolioFilterDTO;
use App\Portfolio\Domain\Entity\CryptoPortfolio;

interface CryptoPortfolioReadRepositoryInterface
{
    /**
     * Найти одну запись портфеля по ID криптовалюты (crypto_currency_id)
     *
     * @param int $cryptoCurrencyId
     * @return CryptoPortfolio|null
     */
    public function findOneByCryptoCurrencyId(int $cryptoCurrencyId): ?CryptoPortfolio;

    /**
     * Найти записи портфеля по фильтрам с пагинацией
     *
     * @param CryptoPortfolioFilterDTO $filter
     * @return array<CryptoPortfolio>
     */
    public function findByFilter(CryptoPortfolioFilterDTO $filter): array;
}
