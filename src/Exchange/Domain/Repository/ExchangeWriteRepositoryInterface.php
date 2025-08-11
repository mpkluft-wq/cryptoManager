<?php

declare(strict_types=1);

namespace App\Exchange\Domain\Repository;

use App\Exchange\Domain\Entity\Exchange;

interface ExchangeWriteRepositoryInterface
{
    /**
     * Сохранить биржу
     *
     * @param Exchange $exchange Объект биржи
     * @return void
     */
    public function save(Exchange $exchange): void;

    /**
     * Удалить биржу
     *
     * @param Exchange $exchange Объект биржи
     * @return void
     */
    public function delete(Exchange $exchange): void;

    /**
     * Применить изменения в хранилище
     * 
     * @return void
     */
    public function flush(): void;
}