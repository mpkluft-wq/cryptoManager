<?php

declare(strict_types=1);

namespace App\Transaction\Domain\Repository;

use App\Transaction\Domain\Entity\Transaction;

interface TransactionWriteRepositoryInterface
{
    /**
     * Сохранить одну транзакцию
     *
     * @param Transaction $transaction
     */
    public function save(Transaction $transaction): void;

    /**
     * Сохранить пакет транзакций
     *
     * @param array<Transaction> $transactions
     */
    public function saveBatch(array $transactions): void;

    /**
     * Удалить транзакцию
     *
     * @param Transaction $transaction
     */
    public function delete(Transaction $transaction): void;

    /**
     * Применить изменения в хранилище
     */
    public function flush(): void;
}
