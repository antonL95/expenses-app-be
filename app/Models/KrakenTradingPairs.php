<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KrakenTradingPairs extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_pair',
        'crypto',
        'fiat',
    ];

    public function currentPrice(): HasMany
    {
        return $this->hasMany(KrakenKeyPairCurrentPrice::class, 'key_pair_id');
    }
}
