<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Создание таблицы transaction для домена транзакций
 */
final class Version20250818092800CreateTransactionTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Создание таблицы transaction для хранения транзакций';
    }

    public function up(Schema $schema): void
    {
        // Postgres: создаём таблицу согласно XML-маппингу
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS transaction (
    id VARCHAR(32) NOT NULL,
    timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    type VARCHAR(20) NOT NULL,
    exchange_id VARCHAR(255) NOT NULL,
    trading_bot_id VARCHAR(255) DEFAULT NULL,
    asset_symbol VARCHAR(20) NOT NULL,
    quantity DOUBLE PRECISION NOT NULL,
    price_per_unit DOUBLE PRECISION NOT NULL,
    total_amount DOUBLE PRECISION NOT NULL,
    currency VARCHAR(20) NOT NULL,
    external_transaction_id VARCHAR(255) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    CONSTRAINT pk_transaction PRIMARY KEY (id)
);
SQL);

        // Полезные индексы для фильтров/сортировок
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_transaction_timestamp ON transaction (timestamp DESC)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_transaction_exchange ON transaction (exchange_id)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_transaction_trading_bot ON transaction (trading_bot_id)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_transaction_type ON transaction (type)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS transaction');
    }
}
