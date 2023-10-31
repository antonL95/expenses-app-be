<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\ChainType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCryptoWallets extends Model
{
    protected $fillable = [
        'userId',
        'walletAddress',
        'chainType',
        'balanceInUsd',
        'erc20Token',
    ];

    protected $casts = [
        'erc20Token' => 'array',
        'balanceInUsd' => 'float',
        'chainType' => ChainType::class,
    ];

    /**
     * @return BelongsTo<User, UserCryptoWallets>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
