<?php

declare(strict_types=1);

namespace App\CryptoCurrency\Domain\Repository;

use App\CryptoCurrency\Domain\DTO\CryptoCurrencyFilterDTO;
use App\CryptoCurrency\Domain\Entity\CryptoCurrency;

interface CryptoCurrencyReadRepositoryInterface
{
    /**
     * Найти криптовалюту по ID
     *
     * @param int $id ID криптовалюты
     * @return CryptoCurrency|null Найденная криптовалюта или null, если не найдена
     */
    public function findById(int $id): ?CryptoCurrency;

    /**
     * Найти несколько криптовалют по заданным критериям
     *
     * @param CryptoCurrencyFilterDTO $filter Параметры фильтрации и пагинации
     * @return array<CryptoCurrency> Массив найденных криптовалют
     */
    public function findByFilter(CryptoCurrencyFilterDTO $filter): array;

    /**
     * Получить общее количество криптовалют по заданным критериям (без учета пагинации)
     *
     * @param CryptoCurrencyFilterDTO $filter Параметры фильтрации
     * @return int Общее количество записей
     */
    public function countByFilter(CryptoCurrencyFilterDTO $filter): int;
}