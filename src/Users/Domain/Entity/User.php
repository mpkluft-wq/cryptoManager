<?php

declare(strict_types=1);

namespace App\Users\Domain\Entity;

use App\Shared\Domain\Service\UlidService;

class User
{
    private readonly string $ulid;

    public function __construct(
        private readonly string $name,
        private readonly string $password,
    ) {
        $this->ulid = UlidService::generate();
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUlid(): string
    {
        return $this->ulid;
    }
}