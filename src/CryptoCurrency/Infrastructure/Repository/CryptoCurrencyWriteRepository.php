<?php

declare(strict_types=1);

namespace App\CryptoCurrency\Infrastructure\Repository;

use App\CryptoCurrency\Domain\Entity\CryptoCurrency;
use App\CryptoCurrency\Domain\Repository\CryptoCurrencyWriteRepositoryInterface;
use App\Shared\Infrastructure\Database\ORM\BaseRepository;

final class CryptoCurrencyWriteRepository extends BaseRepository implements CryptoCurrencyWriteRepositoryInterface
{
    protected function getFQDN(): string
    {
        return CryptoCurrency::class;
    }

    public function save(CryptoCurrency $cryptoCurrency): void
    {
        $this->passThroughExceptionWrapper(function () use ($cryptoCurrency) {
            $this->entityManager->persist($cryptoCurrency);
        });
    }

    public function delete(CryptoCurrency $cryptoCurrency): void
    {
        $this->passThroughExceptionWrapper(function () use ($cryptoCurrency) {
            $this->entityManager->remove($cryptoCurrency);
        });
    }

    public function flush(): void
    {
        $this->passThroughExceptionWrapper(function () {
            $this->entityManager->flush();
        });
    }
}