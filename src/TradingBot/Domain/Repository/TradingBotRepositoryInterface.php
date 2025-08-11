<?php

declare(strict_types=1);

namespace App\TradingBot\Domain\Repository;

use App\TradingBot\Domain\Entity\TradingBot;
use App\TradingBot\Domain\Params\GetAllParams;

interface TradingBotRepositoryInterface
{
    public function save(TradingBot $tradingBot): bool;

    /**
     * @return TradingBot[]
     */
    public function getAll(GetAllParams $params): array;
}