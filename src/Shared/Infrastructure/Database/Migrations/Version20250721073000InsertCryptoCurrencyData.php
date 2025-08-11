<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250721073000InsertCryptoCurrencyData extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Начальное заполнение таблицы cryptocurrency с базовыми активами (с id через DEFAULT)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO crypto_currency (
                id, symbol, name, is_stablecoin, decimals, logo_path, launch_year,
                project_url, explorer_url, blockchain_type, network, contract_address,
                created_at, updated_at
            ) VALUES
                (1, 'BTC', 'Bitcoin', false, 8, NULL, 2009, 'https://bitcoin.org', 'https://www.blockchain.com/explorer', 'bitcoin', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (2, 'ETH', 'Ethereum', false, 18, NULL, 2015, 'https://ethereum.org', 'https://etherscan.io', 'ethereum', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (3, 'TON', 'Toncoin', false, 9, NULL, 2022, 'https://ton.org', 'https://tonscan.org', 'ton', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (4, 'BUILD', 'BUILD', false, 9, NULL, NULL, NULL, 'https://tonscan.org', 'ton', NULL, 'EQBpBbuildContract123', CURRENT_TIMESTAMP, NULL),
                (5, 'MEMHASH', 'Memhash', false, 9, NULL, NULL, NULL, 'https://tonscan.org', 'ton', NULL, 'EQCmemhashAddr456', CURRENT_TIMESTAMP, NULL),
                (6, 'SOL', 'Solana', false, 9, NULL, 2020, 'https://solana.com', 'https://solscan.io', 'solana', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (7, 'MAJOR', 'MAJOR', false, 9, NULL, NULL, NULL, 'https://tonscan.org', 'ton', NULL, 'EQDmajorToken789', CURRENT_TIMESTAMP, NULL),
                (8, 'MANTLE', 'Mantle', false, 18, NULL, NULL, 'https://mantlenetwork.io', 'https://explorer.mantlenetwork.io', 'ethereum', 'erc-20', '0x...mantle', CURRENT_TIMESTAMP, NULL),
                (9, 'MELANIA', 'Official Melania Meme', false, 9, NULL, NULL, NULL, 'https://tonscan.org', 'ton', NULL, 'EQmelaniaMeme123', CURRENT_TIMESTAMP, NULL),
                (10, 'NOT', 'NOT', false, 9, NULL, NULL, NULL, 'https://tonscan.org', 'ton', NULL, 'EQnotJetton456', CURRENT_TIMESTAMP, NULL),
                (11, 'PX', 'PX', false, 9, NULL, NULL, NULL, 'https://tonscan.org', 'ton', NULL, 'EQpxContract789', CURRENT_TIMESTAMP, NULL),

                (12, 'USDT', 'Tether', true, 6, NULL, 2014, 'https://tether.to', 'https://etherscan.io', 'ethereum', 'erc-20', '0xdAC17F958D2ee523a2206206994597C13D831ec7', CURRENT_TIMESTAMP, NULL),
                (13, 'USDC', 'USD Coin', true, 6, NULL, 2018, 'https://www.circle.com', 'https://polygonscan.com', 'polygon', 'erc-20', '0x2791Bca1f2de4661ED88A30C99A7a9449Aa84174', CURRENT_TIMESTAMP, NULL),
                (14, 'BNB', 'Binance Coin', false, 18, NULL, 2017, 'https://www.bnbchain.org', 'https://bscscan.com', 'bsc', 'bep-20', '0xB8c...BNBToken', CURRENT_TIMESTAMP, NULL),
                (15, 'ADA', 'Cardano', false, 6, NULL, 2017, 'https://cardano.org', 'https://cardanoscan.io', 'cardano', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (16, 'AVAX', 'Avalanche', false, 18, NULL, 2020, 'https://avax.network', 'https://avascan.info', 'avalanche', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (17, 'DOGE', 'Dogecoin', false, 8, NULL, 2013, 'https://dogecoin.com', 'https://blockchair.com/dogecoin', 'dogecoin', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (18, 'SHIB', 'Shiba Inu', false, 18, NULL, 2020, 'https://shibatoken.com', 'https://etherscan.io', 'ethereum', 'erc-20', '0x95aD...SHIB', CURRENT_TIMESTAMP, NULL),
                (19, 'TRX', 'Tron', false, 6, NULL, 2017, 'https://tron.network', 'https://tronscan.io', 'tron', NULL, NULL, CURRENT_TIMESTAMP, NULL),
                (20, 'LINK', 'Chainlink', false, 18, NULL, 2017, 'https://chain.link', 'https://etherscan.io', 'ethereum', 'erc-20', '0x514910...LINK', CURRENT_TIMESTAMP, NULL),
                (21, 'XRP', 'Ripple', false, 6, NULL, 2012, 'https://ripple.com/xrp', 'https://xrpscan.com', 'ripple', NULL, NULL, CURRENT_TIMESTAMP, NULL);
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM cryptocurrency WHERE symbol IN (
            'BTC','ETH','TON','BUILD','MEMHASH','SOL','MAJOR','MANTLE','MELANIA','NOT','PX',
            'USDT','USDC','BNB','ADA','AVAX','DOGE','SHIB','TRX','LINK','XRP'
        )");
    }
}
