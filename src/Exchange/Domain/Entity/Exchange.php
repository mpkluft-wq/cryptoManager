<?php

declare(strict_types=1);

namespace App\Exchange\Domain\Entity;

class Exchange
{
    private int $id;

    public function __construct(
        private readonly string $name,
        private readonly bool $isEnabled,
        private readonly string $apiUrl,
        private readonly string $webUrl,
        private readonly ?string $logoPath,
        private readonly int $rateLimit,
        private readonly string $tradingFees,
        private readonly \DateTimeImmutable $createdAt,
        private readonly ?\DateTimeImmutable $updatedAt = null
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getWebUrl(): string
    {
        return $this->webUrl;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function getRateLimit(): int
    {
        return $this->rateLimit;
    }

    public function getTradingFees(): string
    {
        return $this->tradingFees;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
