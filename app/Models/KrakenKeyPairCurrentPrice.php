<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KrakenKeyPairCurrentPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyPairId',
        'lastTradeClosed',
        'ask',
        'bid',
        'low',
        'high',
    ];


    public function keyPair(): BelongsTo
    {
        return $this->belongsTo(KrakenTradingPairs::class, 'keyPairId');
    }
}
