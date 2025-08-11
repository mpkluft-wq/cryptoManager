<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Создание таблицы crypto_portfolio с уникальностью по crypto_currency_id.
 */
final class Version20250819101500CreateTableCryptoPortfolio extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table crypto_portfolio and make crypto_currency_id unique';
    }

    public function up(Schema $schema): void
    {
        // Создаем последовательность для id (PostgreSQL)
        $this->addSql('CREATE SEQUENCE crypto_portfolio_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        // Создаем таблицу
        $this->addSql("CREATE TABLE crypto_portfolio (
            id INT NOT NULL,
            crypto_currency_id INT NOT NULL,
            amount NUMERIC(36, 18) NOT NULL,
            average_price NUMERIC(36, 18) NOT NULL,
            current_price NUMERIC(36, 18) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )");

        // Внешний ключ на crypto_currency(id)
        $this->addSql('ALTER TABLE crypto_portfolio ADD CONSTRAINT fk_crypto_portfolio_crypto_currency_id FOREIGN KEY (crypto_currency_id) REFERENCES crypto_currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Уникальность по crypto_currency_id (одна запись на криптовалюту)
        $this->addSql('ALTER TABLE crypto_portfolio ADD CONSTRAINT uniq_crypto_portfolio_crypto_currency_id UNIQUE (crypto_currency_id)');
    }

    public function down(Schema $schema): void
    {
        // Откатываем изменения
        // Сначала удаляем таблицу (удалит и ограничения)
        $this->addSql('DROP TABLE crypto_portfolio');

        // Удаляем последовательность
        $this->addSql('DROP SEQUENCE crypto_portfolio_id_seq CASCADE');
    }
}
