<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720110553CreateTableTradingBot extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE trading_bot_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE trading_bot (id INT NOT NULL, bot_name VARCHAR(255) NOT NULL, exchange_id SMALLINT NOT NULL, trading_pair_base_id SMALLINT NOT NULL, trading_pair_quote_id SMALLINT NOT NULL, range_price_from NUMERIC(13, 5) NOT NULL, range_price_to NUMERIC(13, 5) NOT NULL, grid_count SMALLINT NOT NULL, step NUMERIC(10, 5) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE trading_bot_id_seq CASCADE');
        $this->addSql('DROP TABLE trading_bot');
    }
}
