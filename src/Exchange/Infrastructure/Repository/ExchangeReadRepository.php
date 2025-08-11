<?php

declare(strict_types=1);

namespace App\Exchange\Infrastructure\Repository;

use App\Exchange\Domain\DTO\ExchangeFilterDTO;
use App\Exchange\Domain\Entity\Exchange;
use App\Exchange\Domain\Repository\ExchangeReadRepositoryInterface;
use App\Shared\Infrastructure\Database\ORM\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

final class ExchangeReadRepository extends BaseRepository implements ExchangeReadRepositoryInterface
{
    protected function getFQDN(): string
    {
        return Exchange::class;
    }

    public function findById(int $id): ?Exchange
    {
        return $this->passThroughExceptionWrapper(function () use ($id) {
            return $this->repository->find($id);
        });
    }

    public function findByFilter(ExchangeFilterDTO $filter): array
    {
        return $this->passThroughExceptionWrapper(function () use ($filter) {
            $queryBuilder = $this->createFilterQueryBuilder($filter);
            $queryBuilder->setFirstResult($filter->getOffset());
            $queryBuilder->setMaxResults($filter->getLimit());

            foreach ($filter->getSort() as $field => $direction) {
                $queryBuilder->addOrderBy('e.' . $field, $direction);
            }

            return $queryBuilder->getQuery()->getResult();
        });
    }

    public function countByFilter(ExchangeFilterDTO $filter): int
    {
        return $this->passThroughExceptionWrapper(function () use ($filter) {
            $queryBuilder = $this->createFilterQueryBuilder($filter);
            $queryBuilder->select('COUNT(e.id)');

            try {
                return (int) $queryBuilder->getQuery()->getSingleScalarResult();
            } catch (NoResultException|NonUniqueResultException) {
                return 0;
            }
        });
    }

    private function createFilterQueryBuilder(ExchangeFilterDTO $filter): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('e')
            ->from($this->getFQDN(), 'e');

        if ($filter->hasIds()) {
            $queryBuilder->andWhere('e.id IN (:ids)')
                ->setParameter('ids', $filter->getIds());
        }

        return $queryBuilder;
    }
}