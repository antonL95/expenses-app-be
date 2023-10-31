<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinGeckoCurrentMarketPrices extends Model
{
    protected $fillable = [
        'coinId',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];
}
