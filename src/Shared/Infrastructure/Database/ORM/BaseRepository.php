<?php

namespace App\Shared\Infrastructure\Database\ORM;

use App\Infrastructure\Exception\InfrastructureGenericException;
use App\Infrastructure\Exception\PassableException;
use App\Infrastructure\Exception\VendorGenericException;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Base repository with Doctrine EntityManager.
 * In general each Doctrine repository should extends this class.
 * Rule of thumb will be encapsulate each read/write call into passThroughExceptionWrapper method
 * to avoid loosing storage or vendor external exceptions.
 */
abstract class BaseRepository extends AbstractRepository
{
    protected EntityManager $entityManager;

    /** @phpstan-ignore-next-line */
    protected EntityRepository $repository;

    public function __construct(EntityManager $entityManager)
    {
        /**
         * @var EntityRepository $repository
         *
         * @phpstan-ignore-next-line
         *
         * @psalm-suppress ArgumentTypeCoercion
         */
        $repository = $entityManager->getRepository($this->getFQDN());

        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    /**
     * Clear unit of work for specified FQDN
     */
    public function clearEntityManager(): void
    {
        $this->entityManager->clear($this->getFQDN());
    }

    /**
     * Выполняет колбэк внутри try-catch-блока, конвертируя все возникшие исключения в предусмотренные для приложения.
     *
     * @param callable $closure
     *А
     *
     * @return mixed
     *
     * @throws InfrastructureGenericException При ошибках работы с БД, хранилищем и т.п.
     * @throws VendorGenericException         При ошибках работы вендор-пакетов, клиентов и т.п.
     */
    protected function passThroughExceptionWrapper(callable $closure): mixed
    {
        try {
            return $closure();
        } catch (PassableException $e) {
            throw $e;
        } catch (DBALException $e) {
            throw new InfrastructureGenericException($e->getMessage(), $e->getCode(), $e->getPrevious());
        } catch (\Exception $e) {
            throw new VendorGenericException($e->getMessage(), (int)$e->getCode(), $e->getPrevious());
        }
    }
}
