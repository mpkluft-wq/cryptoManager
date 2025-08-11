<?php

declare(strict_types=1);

namespace App\Transaction\Infrastructure\Repository;

use App\Shared\Infrastructure\Database\ORM\BaseRepository;
use App\Transaction\Domain\DTO\TransactionFilterDTO;
use App\Transaction\Domain\Entity\Transaction;
use App\Transaction\Domain\Repository\TransactionReadRepositoryInterface;
use Doctrine\ORM\QueryBuilder;

final class TransactionReadRepository extends BaseRepository implements TransactionReadRepositoryInterface
{
    protected function getFQDN(): string
    {
        return Transaction::class;
    }

    public function findById(string $id): ?Transaction
    {
        return $this->passThroughExceptionWrapper(function () use ($id) {
            return $this->repository->find($id);
        });
    }

    public function findByFilter(TransactionFilterDTO $filter): array
    {
        return $this->passThroughExceptionWrapper(function () use ($filter) {
            $qb = $this->createFilterQueryBuilder($filter);

            $qb->setFirstResult($filter->getOffset());
            $qb->setMaxResults($filter->getLimit());

            foreach ($filter->getSort() as $field => $direction) {
                $qb->addOrderBy('t.' . $field, $direction);
            }

            return $qb->getQuery()->getResult();
        });
    }

    private function createFilterQueryBuilder(TransactionFilterDTO $filter): QueryBuilder
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t')
            ->from($this->getFQDN(), 't');

        if ($filter->hasExchangeId()) {
            $qb->andWhere('t.exchangeId = :exchangeId')
               ->setParameter('exchangeId', $filter->getExchangeId());
        }

        if ($filter->hasTradingBotId()) {
            $qb->andWhere('t.tradingBotId = :tradingBotId')
               ->setParameter('tradingBotId', $filter->getTradingBotId());
        }

        return $qb;
    }
}
