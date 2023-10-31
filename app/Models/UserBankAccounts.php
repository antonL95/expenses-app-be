<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBankAccounts extends Model
{
    protected $fillable = [
        'userId',
        'accountName',
        'bankName',
        'accountNumber',
        'bankCode',
        'accountApiToken',
        'accountBalance',
        'accountCurrency',
    ];

    protected $casts = [
        'accountBalance' => 'float',
    ];

    protected $hidden = [
        'accountApiToken',
    ];

    /**
     * @return BelongsTo<User, UserBankAccounts>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
