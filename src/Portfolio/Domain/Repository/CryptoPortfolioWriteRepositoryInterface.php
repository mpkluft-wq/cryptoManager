<?php

declare(strict_types=1);

namespace App\Portfolio\Domain\Repository;

use App\Portfolio\Domain\Entity\CryptoPortfolio;

interface CryptoPortfolioWriteRepositoryInterface
{
    /**
     * Сохранить одну запись портфеля
     *
     * @param CryptoPortfolio $portfolio
     */
    public function save(CryptoPortfolio $portfolio): void;

    /**
     * Сохранить пакет записей портфеля
     *
     * @param array<CryptoPortfolio> $portfolios
     */
    public function saveBatch(array $portfolios): void;

    /**
     * Удалить запись портфеля
     *
     * @param CryptoPortfolio $portfolio
     */
    public function delete(CryptoPortfolio $portfolio): void;

    /**
     * Применить изменения в хранилище
     */
    public function flush(): void;
}
