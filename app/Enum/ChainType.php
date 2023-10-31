<?php

declare(strict_types=1);

namespace App\Enum;

enum ChainType: string
{
    case ETH = 'eth';
    case MATIC = 'matic';
    case BTC = 'btc';
}
