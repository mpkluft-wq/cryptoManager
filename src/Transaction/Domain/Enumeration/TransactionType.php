<?php

declare(strict_types=1);

namespace App\Transaction\Domain\Enumeration;

/**
 * Перечисление типов транзакций в системе
 */
enum TransactionType: string
{
    case BUY = 'buy';
    case SELL = 'sell';
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    case TRANSFER = 'transfer';
    case FEE = 'fee';
    case REBATE = 'rebate';
    case INTEREST = 'interest';
    case STAKING_REWARD = 'staking_reward';
    case MINING_REWARD = 'mining_reward';
    case AIRDROP = 'airdrop';
    case OTHER = 'other';
}