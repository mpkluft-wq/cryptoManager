<?php

declare(strict_types=1);

namespace App\Exchange\Domain\Repository;

use App\Exchange\Domain\DTO\ExchangeFilterDTO;
use App\Exchange\Domain\Entity\Exchange;

interface ExchangeReadRepositoryInterface
{
    /**
     * Найти биржу по ID
     *
     * @param int $id ID биржи
     * @return Exchange|null Найденная биржа или null, если не найдена
     */
    public function findById(int $id): ?Exchange;

    /**
     * Найти несколько бирж по заданным критериям
     *
     * @param ExchangeFilterDTO $filter Параметры фильтрации и пагинации
     * @return array<Exchange> Массив найденных бирж
     */
    public function findByFilter(ExchangeFilterDTO $filter): array;

    /**
     * Получить общее количество бирж по заданным критериям (без учета пагинации)
     *
     * @param ExchangeFilterDTO $filter Параметры фильтрации
     * @return int Общее количество записей
     */
    public function countByFilter(ExchangeFilterDTO $filter): int;
}