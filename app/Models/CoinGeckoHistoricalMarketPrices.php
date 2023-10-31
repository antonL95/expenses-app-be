<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinGeckoHistoricalMarketPrices extends Model
{
    protected $fillable = [
        'coinId',
        'price',
    ];
}