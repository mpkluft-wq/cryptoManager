<?php

declare(strict_types=1);

namespace App\Portfolio\Infrastructure\Repository;

use App\Portfolio\Domain\Entity\CryptoPortfolio;
use App\Portfolio\Domain\Repository\CryptoPortfolioWriteRepositoryInterface;
use App\Shared\Infrastructure\Database\ORM\BaseRepository;

/**
 * Репозиторий записи для CryptoPortfolio
 */
final class CryptoPortfolioWriteRepository extends BaseRepository implements CryptoPortfolioWriteRepositoryInterface
{
    protected function getFQDN(): string
    {
        return CryptoPortfolio::class;
    }

    public function save(CryptoPortfolio $portfolio): void
    {
        $this->passThroughExceptionWrapper(function () use ($portfolio) {
            $this->entityManager->persist($portfolio);
        });
    }

    /**
     * @param array<CryptoPortfolio> $portfolios
     */
    public function saveBatch(array $portfolios): void
    {
        $this->passThroughExceptionWrapper(function () use ($portfolios) {
            foreach ($portfolios as $portfolio) {
                if ($portfolio instanceof CryptoPortfolio) {
                    $this->entityManager->persist($portfolio);
                }
            }
        });
    }

    public function delete(CryptoPortfolio $portfolio): void
    {
        $this->passThroughExceptionWrapper(function () use ($portfolio) {
            $this->entityManager->remove($portfolio);
        });
    }

    public function flush(): void
    {
        $this->passThroughExceptionWrapper(function () {
            $this->entityManager->flush();
        });
    }
}
