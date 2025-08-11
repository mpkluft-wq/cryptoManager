<?php

declare(strict_types=1);

namespace App\Portfolio\Infrastructure\Repository;

use App\Portfolio\Domain\DTO\CryptoPortfolioFilterDTO;
use App\Portfolio\Domain\Entity\CryptoPortfolio;
use App\Portfolio\Domain\Repository\CryptoPortfolioReadRepositoryInterface;
use App\Shared\Infrastructure\Database\ORM\BaseRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Репозиторий чтения для CryptoPortfolio
 */
final class CryptoPortfolioReadRepository extends BaseRepository implements CryptoPortfolioReadRepositoryInterface
{
    protected function getFQDN(): string
    {
        return CryptoPortfolio::class;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByCryptoCurrencyId(int $cryptoCurrencyId): ?CryptoPortfolio
    {
        return $this->passThroughExceptionWrapper(function () use ($cryptoCurrencyId) {
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('cp')
                ->from($this->getFQDN(), 'cp')
                ->andWhere('IDENTITY(cp.cryptoCurrency) = :ccId')
                ->setParameter('ccId', $cryptoCurrencyId)
                ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findByFilter(CryptoPortfolioFilterDTO $filter): array
    {
        return $this->passThroughExceptionWrapper(function () use ($filter) {
            $qb = $this->createFilterQueryBuilder($filter);

            $qb->setFirstResult($filter->getOffset());
            $qb->setMaxResults($filter->getLimit());

            foreach ($filter->getSort() as $field => $direction) {
                $qb->addOrderBy('cp.' . $field, $direction);
            }

            return $qb->getQuery()->getResult();
        });
    }

    private function createFilterQueryBuilder(CryptoPortfolioFilterDTO $filter): QueryBuilder
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('cp')
            ->from($this->getFQDN(), 'cp');

        if ($filter->hasCryptoCurrencyIds()) {
            $qb->andWhere('IDENTITY(cp.cryptoCurrency) IN (:ccIds)')
               ->setParameter('ccIds', $filter->getCryptoCurrencyIds());
        }

        return $qb;
    }
}
