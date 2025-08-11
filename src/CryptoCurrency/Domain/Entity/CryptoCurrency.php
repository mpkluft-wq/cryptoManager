<?php

declare(strict_types=1);

namespace App\CryptoCurrency\Domain\Entity;

class CryptoCurrency
{
    private int $id;

    public function __construct(
        private readonly string $symbol,
        private readonly string $name,
        private readonly bool $isStablecoin,
        private readonly int $decimals,
        private readonly ?string $logoPath,
        private readonly ?int $launchYear,
        private readonly ?string $projectUrl,
        private readonly ?string $explorerUrl,
        private readonly string $blockchainType,
        private readonly ?string $network,
        private readonly ?string $contractAddress,
        private readonly \DateTimeImmutable $createdAt,
        private readonly ?\DateTimeImmutable $updatedAt = null
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isStablecoin(): bool
    {
        return $this->isStablecoin;
    }

    public function getDecimals(): int
    {
        return $this->decimals;
    }

    public function getLogoPath(): ?string
    {
        return $this->logoPath;
    }

    public function getLaunchYear(): ?int
    {
        return $this->launchYear;
    }

    public function getProjectUrl(): ?string
    {
        return $this->projectUrl;
    }

    public function getExplorerUrl(): ?string
    {
        return $this->explorerUrl;
    }

    public function getBlockchainType(): string
    {
        return $this->blockchainType;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function getContractAddress(): ?string
    {
        return $this->contractAddress;
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
