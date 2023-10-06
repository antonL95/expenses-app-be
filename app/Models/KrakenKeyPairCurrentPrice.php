<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KrakenKeyPairCurrentPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_pair_id',
        'last_trade_closed',
        'ask',
        'bid',
        'low',
        'high',
    ];

    protected $casts = [
        'last_trade_closed' => 'decimal:6',
        'ask' => 'decimal:6',
        'bid' => 'decimal:6',
        'low' => 'decimal:6',
        'high' => 'decimal:6',
    ];


    public function keyPair(): BelongsTo
    {
        return $this->belongsTo(KrakenTradingPairs::class, 'key_pair_id');
    }
}
