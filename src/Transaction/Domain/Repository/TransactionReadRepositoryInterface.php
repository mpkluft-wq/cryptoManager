<?php

declare(strict_types=1);

namespace App\Transaction\Domain\Repository;

use App\Transaction\Domain\DTO\TransactionFilterDTO;
use App\Transaction\Domain\Entity\Transaction;

interface TransactionReadRepositoryInterface
{
    /**
     * Найти транзакцию по ID
     *
     * @param string $id ULID транзакции
     * @return Transaction|null
     */
    public function findById(string $id): ?Transaction;

    /**
     * Найти транзакции по фильтрам с пагинацией
     *
     * @param TransactionFilterDTO $filter Параметры фильтрации и пагинации
     * @return array<Transaction>
     */
    public function findByFilter(TransactionFilterDTO $filter): array;
}
