<?php

declare(strict_types=1);

namespace App\TradingBot\Domain\Params;

class GetAllParams
{
    public function __construct(
        private readonly ?int $id = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
