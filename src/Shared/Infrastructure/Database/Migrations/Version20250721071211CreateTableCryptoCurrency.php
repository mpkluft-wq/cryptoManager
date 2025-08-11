<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721071211CreateTableCryptoCurrency extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE crypto_currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE crypto_currency (id INT NOT NULL, symbol VARCHAR(20) NOT NULL, name VARCHAR(255) NOT NULL, is_stablecoin BOOLEAN NOT NULL, decimals INT NOT NULL, logo_path VARCHAR(255) DEFAULT NULL, launch_year INT DEFAULT NULL, project_url VARCHAR(255) DEFAULT NULL, explorer_url VARCHAR(255) DEFAULT NULL, blockchain_type VARCHAR(50) NOT NULL, network VARCHAR(50) DEFAULT NULL, contract_address VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE crypto_currency_id_seq CASCADE');
        $this->addSql('DROP TABLE crypto_currency');
    }
}
