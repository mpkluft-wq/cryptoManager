<?php

declare(strict_types=1);

namespace App\TradingBot\Infrastructure\Repository;

use App\Shared\Infrastructure\Database\ORM\BaseRepository;
use App\TradingBot\Domain\Entity\TradingBot;
use App\TradingBot\Domain\Params\GetAllParams;
use App\TradingBot\Domain\Repository\TradingBotRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\QueryBuilder;

class TradingBotRepository extends BaseRepository implements TradingBotRepositoryInterface
{
    private const SORTING_MAP = [
        'id' => 'tb.id',
    ];

    public function save(TradingBot $tradingBot): bool
    {
        return $this->passThroughExceptionWrapper(function () use ($tradingBot): bool {
            try {
                $this->entityManager->persist($tradingBot);
                $this->entityManager->flush();
                $this->entityManager->clear($this->getFQDN());

                return true;
            } catch (UniqueConstraintViolationException) {
                return false;
            }
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(GetAllParams $params): array
    {
        return $this->passThroughExceptionWrapper(function () use ($params): array {
            $builder = $this->repository->createQueryBuilder('tb');
            $this->applyFilter($builder, $params);
//            $this->addOrderByWithMapping($builder, $params, self::SORTING_MAP);

            return $builder->getQuery()->getResult();
        });
    }
    private function applyFilter(QueryBuilder $builder, GetAllParams $param): void
    {
        if ($param->getId() !== null) {
            $builder
                ->andWhere('tb.id = :id')
                ->setParameter('id', $param->getId());
        }
    }
    /**
     * {@inheritDoc}
     */
    protected function getFQDN(): string
    {
        return TradingBot::class;
    }
}