<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KrakenTradingPairs extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_pair',
        'crypto',
        'fiat',
    ];
}
