<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Вставка ~10 основных DEX-бирж (включая Ston.fi) в таблицу exchange.
 * Примечание: id задаются явно (11..20), поскольку у таблицы нет DEFAULT nextval() для id.
 */
final class Version20250818121300InsertDexExchanges extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление ~10 основных DEX (включая Ston.fi) в таблицу exchange';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("\n            INSERT INTO exchange (id, name, is_enabled, api_url, web_url, logo_path, rate_limit, trading_fees, created_at, updated_at)\n            VALUES\n                (11, 'Uniswap', true, 'https://api.thegraph.com/subgraphs/name/uniswap/uniswap-v3', 'https://uniswap.org', NULL, 1200, 0.003, NOW(), NULL),\n                (12, 'SushiSwap', true, 'https://api.thegraph.com/subgraphs/name/sushiswap/exchange', 'https://www.sushi.com', NULL, 1200, 0.003, NOW(), NULL),\n                (13, 'PancakeSwap', true, 'https://api.bscscan.com/api', 'https://pancakeswap.finance', NULL, 1200, 0.003, NOW(), NULL),\n                (14, 'Curve Finance', true, 'https://api.curve.fi/', 'https://curve.fi', NULL, 1200, 0.003, NOW(), NULL),\n                (15, 'Balancer', true, 'https://api.thegraph.com/subgraphs/name/balancer-labs/balancer-v2', 'https://balancer.fi', NULL, 1200, 0.003, NOW(), NULL),\n                (16, 'QuickSwap', true, 'https://api.thegraph.com/subgraphs/name/sameepsi/quickswap06', 'https://quickswap.exchange', NULL, 1200, 0.003, NOW(), NULL),\n                (17, 'Trader Joe', true, 'https://api.thegraph.com/subgraphs/name/traderjoe-xyz/exchange', 'https://traderjoexyz.com', NULL, 1200, 0.003, NOW(), NULL),\n                (18, 'Raydium', true, 'https://api.raydium.io/', 'https://raydium.io', NULL, 1200, 0.003, NOW(), NULL),\n                (19, 'Orca', true, 'https://api.mainnet.orca.so/', 'https://www.orca.so', NULL, 1200, 0.003, NOW(), NULL),\n                (20, 'Ston.fi', true, 'https://ston.fi/api', 'https://ston.fi', NULL, 1200, 0.003, NOW(), NULL)\n        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM exchange WHERE id BETWEEN 11 AND 20');
    }
}
