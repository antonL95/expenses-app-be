<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CoinGeckoCoinList extends Model
{
    protected $fillable = [
        'coinId',
        'tokenName',
        'tokenTicker',
        'platforms',
    ];

    protected $casts = [
        'platforms' => 'array',
    ];

    public function marketPrices(): HasOne
    {
        return $this->hasOne(CoinGeckoCurrentMarketPrices::class, 'coinId', 'coinId');
    }
}
