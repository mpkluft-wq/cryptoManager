<?php

declare(strict_types=1);

namespace App\CryptoCurrency\Infrastructure\Repository;

use App\CryptoCurrency\Domain\DTO\CryptoCurrencyFilterDTO;
use App\CryptoCurrency\Domain\Entity\CryptoCurrency;
use App\CryptoCurrency\Domain\Repository\CryptoCurrencyReadRepositoryInterface;
use App\Shared\Infrastructure\Database\ORM\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

final class CryptoCurrencyReadRepository extends BaseRepository implements CryptoCurrencyReadRepositoryInterface
{
    protected function getFQDN(): string
    {
        return CryptoCurrency::class;
    }

    public function findById(int $id): ?CryptoCurrency
    {
        return $this->passThroughExceptionWrapper(function () use ($id) {
            return $this->repository->find($id);
        });
    }

    public function findByFilter(CryptoCurrencyFilterDTO $filter): array
    {
        return $this->passThroughExceptionWrapper(function () use ($filter) {
            $queryBuilder = $this->createFilterQueryBuilder($filter);
            $queryBuilder->setFirstResult($filter->getOffset());
            $queryBuilder->setMaxResults($filter->getLimit());

            foreach ($filter->getSort() as $field => $direction) {
                $queryBuilder->addOrderBy('cc.' . $field, $direction);
            }

            return $queryBuilder->getQuery()->getResult();
        });
    }

    public function countByFilter(CryptoCurrencyFilterDTO $filter): int
    {
        return $this->passThroughExceptionWrapper(function () use ($filter) {
            $queryBuilder = $this->createFilterQueryBuilder($filter);
            $queryBuilder->select('COUNT(cc.id)');

            try {
                return (int) $queryBuilder->getQuery()->getSingleScalarResult();
            } catch (NoResultException|NonUniqueResultException) {
                return 0;
            }
        });
    }

    private function createFilterQueryBuilder(CryptoCurrencyFilterDTO $filter): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('cc')
            ->from($this->getFQDN(), 'cc');

        if ($filter->hasIds()) {
            $queryBuilder->andWhere('cc.id IN (:ids)')
                ->setParameter('ids', $filter->getIds());
        }

        return $queryBuilder;
    }
}