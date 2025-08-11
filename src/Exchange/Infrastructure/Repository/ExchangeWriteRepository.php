<?php

declare(strict_types=1);

namespace App\Exchange\Infrastructure\Repository;

use App\Exchange\Domain\Entity\Exchange;
use App\Exchange\Domain\Repository\ExchangeWriteRepositoryInterface;
use App\Shared\Infrastructure\Database\ORM\BaseRepository;

final class ExchangeWriteRepository extends BaseRepository implements ExchangeWriteRepositoryInterface
{
    protected function getFQDN(): string
    {
        return Exchange::class;
    }

    public function save(Exchange $exchange): void
    {
        $this->passThroughExceptionWrapper(function () use ($exchange) {
            $this->entityManager->persist($exchange);
        });
    }

    public function delete(Exchange $exchange): void
    {
        $this->passThroughExceptionWrapper(function () use ($exchange) {
            $this->entityManager->remove($exchange);
        });
    }

    public function flush(): void
    {
        $this->passThroughExceptionWrapper(function () {
            $this->entityManager->flush();
        });
    }
}