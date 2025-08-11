<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250721060000InsertExchanges extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавление 10 популярных криптобирж в таблицу exchange';
    }

    public function up(Schema $schema): void
    {
        // Вставка основных бирж
        $this->addSql("
            INSERT INTO exchange (id, name, is_enabled, api_url, web_url, logo_path, rate_limit, trading_fees, created_at, updated_at)
            VALUES
                (1, 'Binance', true, 'https://api.binance.com', 'https://www.binance.com', NULL, 1200, 0.001, NOW(), NULL),
                (2, 'Bybit', true, 'https://api.bybit.com', 'https://www.bybit.com', NULL, 1000, 0.001, NOW(), NULL),
                (3, 'Kraken', true, 'https://api.kraken.com', 'https://www.kraken.com', NULL, 600, 0.002, NOW(), NULL),
                (4, 'Coinbase Pro', true, 'https://api.exchange.coinbase.com', 'https://www.coinbase.com', NULL, 300, 0.005, NOW(), NULL),
                (5, 'KuCoin', true, 'https://api.kucoin.com', 'https://www.kucoin.com', NULL, 900, 0.001, NOW(), NULL),
                (6, 'OKX', true, 'https://www.okx.com/api', 'https://www.okx.com', NULL, 1500, 0.001, NOW(), NULL),
                (7, 'Gate.io', true, 'https://api.gate.io', 'https://www.gate.io', NULL, 1200, 0.002, NOW(), NULL),
                (8, 'MEXC', true, 'https://api.mexc.com', 'https://www.mexc.com', NULL, 1000, 0.001, NOW(), NULL),
                (9, 'Bitget', true, 'https://api.bitget.com', 'https://www.bitget.com', NULL, 1000, 0.001, NOW(), NULL),
                (10, 'Bitfinex', true, 'https://api.bitfinex.com', 'https://www.bitfinex.com', NULL, 1200, 0.002, NOW(), NULL);
        ");
    }

    public function down(Schema $schema): void
    {
        // Откат вставки
        $this->addSql('DELETE FROM exchange WHERE id BETWEEN 1 AND 10');
    }
}
