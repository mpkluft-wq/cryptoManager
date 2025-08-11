<?php

declare(strict_types=1);

namespace App\Transaction\Infrastructure\Repository;

use App\Shared\Infrastructure\Database\ORM\BaseRepository;
use App\Transaction\Domain\Entity\Transaction;
use App\Transaction\Domain\Repository\TransactionWriteRepositoryInterface;

final class TransactionWriteRepository extends BaseRepository implements TransactionWriteRepositoryInterface
{
    protected function getFQDN(): string
    {
        return Transaction::class;
    }

    public function save(Transaction $transaction): void
    {
        $this->passThroughExceptionWrapper(function () use ($transaction) {
            $this->entityManager->persist($transaction);
        });
    }

    /**
     * @param array<Transaction> $transactions
     */
    public function saveBatch(array $transactions): void
    {
        $this->passThroughExceptionWrapper(function () use ($transactions) {
            foreach ($transactions as $transaction) {
                if ($transaction instanceof Transaction) {
                    $this->entityManager->persist($transaction);
                }
            }
        });
    }

    public function delete(Transaction $transaction): void
    {
        $this->passThroughExceptionWrapper(function () use ($transaction) {
            $this->entityManager->remove($transaction);
        });
    }

    public function flush(): void
    {
        $this->passThroughExceptionWrapper(function () {
            $this->entityManager->flush();
        });
    }
}
