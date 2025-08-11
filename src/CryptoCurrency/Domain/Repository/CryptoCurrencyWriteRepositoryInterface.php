<?php

declare(strict_types=1);

namespace App\CryptoCurrency\Domain\Repository;

use App\CryptoCurrency\Domain\Entity\CryptoCurrency;

interface CryptoCurrencyWriteRepositoryInterface
{
    /**
     * Сохранить криптовалюту
     *
     * @param CryptoCurrency $cryptoCurrency Объект криптовалюты
     * @return void
     */
    public function save(CryptoCurrency $cryptoCurrency): void;

    /**
     * Удалить криптовалюту
     *
     * @param CryptoCurrency $cryptoCurrency Объект криптовалюты
     * @return void
     */
    public function delete(CryptoCurrency $cryptoCurrency): void;

    /**
     * Применить изменения в хранилище
     * 
     * @return void
     */
    public function flush(): void;
}